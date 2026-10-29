<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['label', 'amount_text', 'fee_type', 'sort_order', 'is_active'])]
class RegistrationFee extends Model
{
    public const TYPE_ONE_TIME = 'one_time';

    public const TYPE_MONTHLY = 'monthly';

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }
}

