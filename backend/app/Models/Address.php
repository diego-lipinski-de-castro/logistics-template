<?php

namespace App\Models;

use Ajthinking\LaravelPostgis\Eloquent\PostgisTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Address extends Model implements Auditable
{
    use HasFactory;
    use PostgisTrait;
    use \OwenIt\Auditing\Auditable;

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'position',
        'street_number',
        'street_name',
        'district',
        'city',
        'state',
        'cep',
    ];

    protected $postgisFields = [
        'position',
    ];

    protected $postgisTypes = [
        'position' => [
            'geomtype' => 'geography',
            'srid' => 3557,
        ],
    ];

    protected $appends = [
        'formatted_address',
    ];

    public function getFormattedAddressAttribute(): string
    {
        return "{$this->street_name}, {$this->street_number} - {$this->district}, {$this->city} - {$this->state}";
    }
}
