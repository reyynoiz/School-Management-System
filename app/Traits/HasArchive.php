<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasArchive
{
    public static function bootHasArchive(): void
    {
        static::addGlobalScope('notArchived', function (Builder $builder) {
            $builder->where($builder->getModel()->getTable() . '.archived', 0);
        });

        static::creating(function ($model) {
            if (is_null($model->archived)) {
                $model->archived = 0;
            }
        });
    }

    /**
     * Soft delete: hanya set archived = 1, bukan hapus data sesungguhnya.
     */
    public function archive(): bool
    {
        return $this->update(['archived' => 1]);
    }

    /**
     * Query builder yang menyertakan data yang sudah di-archive juga.
     */
    public function scopeWithArchived(Builder $query): Builder
    {
        return $query->withoutGlobalScope('notArchived');
    }
}