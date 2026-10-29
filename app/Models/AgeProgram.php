<?php

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title',
    'category_group',
    'age_range',
    'coach_name',
    'description',
    'image_path',
    'sort_order',
    'is_active',
])]
class AgeProgram extends Model
{
    use ResolvesMediaUrl;

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<TrainingSchedule, $this>
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(TrainingSchedule::class, 'age_program_id');
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * @return Attribute<string|null, never>
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->resolveMediaUrl($this->image_path));
    }
}

