<?php

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// uploaded_by is assigned server-side from the authenticated user, never from request input.
#[Fillable(['title', 'caption', 'image_path', 'grid_ratio', 'is_highlighted_on_home'])]
class Gallery extends Model
{
    use ResolvesMediaUrl;

    protected function casts(): array
    {
        return [
            'is_highlighted_on_home' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * @return Attribute<string|null, never>
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->resolveMediaUrl($this->image_path));
    }
}

