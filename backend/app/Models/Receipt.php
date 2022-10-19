<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Receipt extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'delivery_id',
        'customer_name',
        'customer_document',
        'picture',
    ];

    protected $appends = [
        'full_url',
    ];

    public function delivery(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function getFullUrlAttribute(): ?string
    {
        if (blank($this->picture)) {
            return null;
        }

        if (! auth()->check()) {
            return null;
        }

        if (auth()->user() instanceof Developer) {
            return route('developer.deliveries.show.receipt', $this->delivery->id);
        }

        return null;
    }
}
