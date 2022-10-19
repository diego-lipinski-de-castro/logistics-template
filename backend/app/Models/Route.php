<?php

namespace App\Models;

use Ajthinking\LaravelPostgis\Eloquent\PostgisTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Route extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use PostgisTrait;

    protected $fillable = [
        'delivery_id',
        'route',
    ];

    protected $postgisFields = [
        'route',
    ];

    protected $postgisTypes = [
        'route' => [
            'geomtype' => 'geography',
            'srid' => 3557,
        ],
    ];
}
