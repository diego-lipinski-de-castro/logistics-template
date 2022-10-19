<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasPubId
{
    public static function bootHasPubId(): void
    {
        static::creating(function (Model $model) {
            $model->setAttribute('pub_id', Str::uuid());
        });
    }
}
