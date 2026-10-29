<?php

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// author_id is assigned server-side from the authenticated user, never from request input.
#[Fillable([
    'category_id',
    'title',
    'slug',
    'lead_paragraph',
    'content',
    'thumbnail_path',
    'is_featured',
    'is_published',
    'published_at',
])]
class News extends Model
{
    use ResolvesMediaUrl;

    protected $table = 'news';

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<NewsCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Any article marked as published in the dashboard is immediately visible on the public site.
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_published', true);
    }

    #[Scope]
    protected function latestPublished(Builder $query): void
    {
        $query->orderByDesc('published_at')->orderByDesc('id');
    }

    /**
     * Same-category articles first, topped up with the latest published articles.
     *
     * @return Collection<int, News>
     */
    public function getRelatedNews(int $limit = 5): Collection
    {
        $sameCategory = static::query()
            ->published()
            ->with('category')
            ->where('category_id', $this->category_id)
            ->whereKeyNot($this->getKey())
            ->latestPublished()
            ->limit($limit)
            ->get();

        if ($sameCategory->count() >= $limit) {
            return $sameCategory;
        }

        $others = static::query()
            ->published()
            ->with('category')
            ->whereKeyNot($this->getKey())
            ->whereNotIn('id', $sameCategory->modelKeys())
            ->latestPublished()
            ->limit($limit - $sameCategory->count())
            ->get();

        return $sameCategory->concat($others)->values();
    }

    /**
     * @return Attribute<string|null, never>
     */
    protected function thumbnailUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->resolveMediaUrl($this->thumbnail_path));
    }

    /**
     * @return Attribute<string, never>
     */
    protected function publishedDateLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->published_at
            ? $this->published_at->copy()->locale('id')->translatedFormat('j F Y')
            : '');
    }

    /**
     * Content is stored as plain text paragraphs separated by blank lines and is escaped on output.
     *
     * @return Attribute<array<int, string>, never>
     */
    protected function paragraphs(): Attribute
    {
        return Attribute::get(fn (): array => array_values(array_filter(
            array_map('trim', preg_split('/\R{2,}/', (string) $this->content) ?: []),
            fn (string $paragraph): bool => $paragraph !== '',
        )));
    }
}

