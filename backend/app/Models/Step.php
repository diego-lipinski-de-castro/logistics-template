<?php

namespace App\Models;

use Ajthinking\LaravelPostgis\Eloquent\PostgisTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Step extends Model implements Auditable
{
    use HasFactory;
    use PostgisTrait;
    use \OwenIt\Auditing\Auditable;

    public const STATUSES = [
        'PENDING' => 'Pendente',
        'COMPLETED' => 'Completo',
        'CURRENT' => 'Em andamento',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'delivery_id',
        'driver_id',

        'location',
        
        'street_number',
        'street_name',
        'district',
        'city',
        'state',
        
        'info',
        
        'prev_id',
        'next_id',

        'status',

        'line',

        'distance',
        'duration',
    ];

    /**
    * Attributes to include in the Audit.
    *
    * @var array
    */
    protected $auditInclude = [
        'status',
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
        'formatted_address',
    ];

    public function getFormattedAddressAttribute(): string
    {
        $address = "{$this->street_name}";

        if (! blank($this->street_number)) {
            $address .= ", {$this->street_number}";
        }

        $address .= " - {$this->district}, {$this->city} - {$this->state}";
        
        if (! blank($this->info)) {
            return $address .= ". Complemento: {$this->info}";
        }

        return $address;
    }
}
