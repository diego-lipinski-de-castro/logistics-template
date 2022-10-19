<?php

namespace App\Models;

use Ajthinking\LaravelPostgis\Eloquent\PostgisTrait;
use App\Traits\HasFormattedDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class City extends Model implements Auditable
{
    use HasFactory;
    use HasFormattedDates;
    use PostgisTrait;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'state_id',
        'name',
        'enabled',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
    ];

    protected $postgisFields = [
        'center',
    ];

    protected $postgisTypes = [
        'center' => [
            'geomtype' => 'geography',
            'srid' => 3557,
        ],
    ];

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeOfState($query, $value)
    {
        return $query->where('state_id', $value);
    }

    public function scopeOfUser($query, $value)
    {
        return $query->whereHas('users', function ($q) use ($value) {
            $q->where('users.id', $value);
        });
    }

    public function scopeOfAdmin($query, $value)
    {
        return $query->whereHas('admins', function ($q) use ($value) {
            $q->where('admins.id', $value);
        });
    }

    public function state(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function admins(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Admin::class);
    }

    public function deliveries(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Delivery::class, User::class);
    }
}
