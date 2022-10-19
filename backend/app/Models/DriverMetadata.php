<?php

namespace App\Models;

use Ajthinking\LaravelPostgis\Eloquent\PostgisTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DriverMetadata extends Model implements Auditable
{
    use HasFactory;
    use PostgisTrait;
    use \OwenIt\Auditing\Auditable;

    protected $touches = ['driver'];

    protected $primaryKey = 'driver_id';

    public const MODES = [
        'BIKE' => 'Bike',
        'E_BIKE' => 'E-bike',
        'MOTO' => 'Moto',
        'CAR' => 'Carro',
        'VAN' => 'Van',
        'TRUCK' => 'Caminhão',
    ];

    public const BAGS = [
        'BAG_45' => 'Bag 45L',
        'BAG_55' => 'Bag 55L',
        'BAG_89' => 'Bag 89L',
        'BAG' => 'Bag',
        'CAR' => 'Carro',
        'OPEN_VAN' => 'Van aberta',
        'CLOSED_VAN' => 'Van fechada',
        'TRUCK' => 'Caminhão',
    ];

    public const STATUSES = [
        'ONLINE' => 'Online',
        'OFFLINE' => 'Offline',
        'BUSY' => 'Ocupado',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'driver_id',

        'cloud',

        'location',
        'status',

        'mode',
        'bag',

        'battery_level',
        'device',

        'version',
        'build',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'cloud' => 'boolean',
    ];

    protected $postgisFields = [
        'location',
    ];

    protected $postgisTypes = [
        'location' => [
            'geomtype' => 'geography',
            'srid' => 3557,
        ],
    ];

    protected $appends = [
        'formatted_status',
    ];

    public function getFormattedStatusAttribute(): ?string
    {
        if (blank($this->status)) {
            return null;
        }

        return self::STATUSES[$this->status];
    }

    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
