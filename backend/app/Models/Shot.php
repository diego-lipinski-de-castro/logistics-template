<?php

namespace App\Models;

use App\Traits\HasFormattedDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Shot extends Model implements Auditable
{
    use HasFactory;
    use HasFormattedDates;
    use \OwenIt\Auditing\Auditable;

    public $timestamps = false;

    public const ACTIONS = [
        'SENT' => 'Enviado',
        'RECEIVED' => 'Recebido',
        'ACCEPTED' => 'Aceito',
        'REFUSED' => 'Recusado',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'delivery_id',
        'driver_id',

        'action',

        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_action',
    ];

    public function delivery(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function getFormattedActionAttribute(): string
    {
        return match ($this->action) {
            'SENT' => 'enviado',
            'RECEIVED' => 'recebeu',
            'ACCEPTED' => 'aceitou',
            'REFUSED' => 'recusou',
        };
    }
}
