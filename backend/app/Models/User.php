<?php

namespace App\Models;

use Ajthinking\LaravelPostgis\Eloquent\PostgisTrait;
use App\Helper;
use App\Traits\HasFormattedDates;
use App\Traits\HasPubId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use PostgisTrait;
    use HasFormattedDates;
    use HasPubId;
    use \OwenIt\Auditing\Auditable;

    public const STATUSES = [
        'PENDING' => 'Pendente',
        'APPROVED' => 'Aprovado',
        'REJECTED' => 'Rejeitado',
    ];

    public const RECEIPT_CONFIRMATION = [
        'DISABLED' => 'Desativado (não irá aparecer a opção de confirmação)',
        'OPTIONAL' => 'Opcional (irá aparecer a opção mas não será obrigatório)',
        'REQUIRED_PARTIAL' => 'Obrigatório (irá aparecer a opção e será obrigatório)',
        'REQUIRED' => 'Obrigatório com foto (irá aparecer a opção e será obrigatório anexar foto)',
    ];

    public const CHARGE_STYLES = [
        'LINE' => 'Linha reta',
        'ROUTE' => 'Rota',
        'OPEN' => 'Livre (Last mile)',
        'DAILY' => 'Diária (Last mile)',
        'PACKAGE' => 'Pacote (Last mile)',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'city_id',

        'name',
        'email',
        'password',

        'phone',
        'info',
        
        'area',
        'radiuses',

        'receipt_confirmation',
        'delivery_constraint',
        
        'return_fee_paid',
        'return_fee_charged',

        'report_period',
        'report_period_option',

        'charge_style',
        'charge_options',

        'status',

        'banned_at',

        'cpf_cnpj',
        'company_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'banned_at' => 'datetime',
    ];

    protected $postgisFields = [
        'area',
    ];

    protected $postgisTypes = [
        'area' => [
            'geomtype' => 'geography',
            'srid' => 3557,
        ],
    ];

    protected $appends = [
        'formatted_return_fee_paid',
        'formatted_return_fee_charged',
        'lastmile',
        'banned',
    ];

    /**
     * {@inheritdoc}
     */
    public function transformAudit(array $data): array
    {
        if (Arr::has($data, 'old_values.radiuses')) {
            $data['old_values']['radiuses'] = collect($this->getOriginal('radiuses'))->map(function ($item) {
                return [
                    'rad' => $item['rad'],
                    'time' => $item['time'],
                    'paid' => Helper::extractNumbersFromString($item['paid']),
                    'charged' => Helper::extractNumbersFromString($item['charged']),
                ];
            });
        }

        return $data;
    }

    public static function scopeOfCity($query, int|array $value)
    {
        if (is_array($value)) {
            return $query->whereHas('cities', function ($q) use ($value) {
                $q->whereIn('cities.id', $value);
            });
        }

        return $query->whereHas('cities', function ($q) use ($value) {
            $q->where('cities.id', $value);
        });
    }

    public function scopeOfCities($query, int|array $value)
    {
        return self::scopeOfCity($query, $value);
    }

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function cities(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(City::class);
    }

    public function drivers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Driver::class);
    }

    public function deliveries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function reports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function getCitiesIdsAttribute(): array
    {
        return $this->cities()->pluck('cities.id')->toArray();
    }

    public function getDriversIdsAttribute(): array
    {
        return $this->drivers()->pluck('drivers.id')->toArray();
    }

    public function getFormattedStatusAttribute(): ?string
    {
        if (blank($this->status)) {
            return null;
        }

        return self::STATUSES[$this->status];
    }

    public function getBannedAttribute(): bool
    {
        return $this->banned_at !== null;
    }

    public function getFormattedReturnFeePaidAttribute(): string
    {
        return Helper::toMoney($this->return_fee_paid ?? 0);
    }

    public function getFormattedReturnFeeChargedAttribute(): string
    {
        return Helper::toMoney($this->return_fee_charged ?? 0);
    }

    public function setRadiusesAttribute($value): void
    {
        if (is_array($value)) {
            $value = collect($value);
        }

        $this->attributes['radiuses'] = $value;
    }

    /**
     * @return \Illuminate\Support\Collection|array
     *
     * @psalm-return \Illuminate\Support\Collection|array<empty, empty>
     */
    public function getRadiusesAttribute($value)
    {
        if (blank($this->area) || blank($value)) {
            return [];
        }

        $collection = collect(json_decode($value, true))
            ->when($this->charge_style == 'LINE', fn ($c) => $c->whereNotNull('rad'))
            ->transform(function ($item) {
                $item['formatted_paid'] = Helper::toMoney($item['paid']);
                $item['formatted_charged'] = Helper::toMoney($item['charged']);
                
                return $item;
            });

        if ($this->charge_style == 'ROUTE' && $collection->last()['rad'] != null) {
            $collection->push([
                'rad' => null,
                'time' => 0,
                'paid' => 0,
                'charged' => 0,
                'formatted_paid' => Helper::toMoney(0),
                'formatted_charged' => Helper::toMoney(0),
            ]);
        }

        return $collection;
    }

    /**
     * @return void
     */
    public function updateRadiuses()
    {
        if (blank($this->radiuses)) {
            $this->update([
                'radiuses' => $this->mockRadiuses(),
            ]);

            return;
        }

        $radiuses = $this->radiuses
            ->whereNotNull('rad')
            ->map(function ($item) {
                return [
                    'rad' => $item['rad'],
                    'time' => $item['time'],
                    'paid' => Helper::extractNumbersFromString($item['paid']),
                    'charged' => Helper::extractNumbersFromString($item['charged']),
                ];
            });

        $mock = $this->mockRadiuses()->whereNotNull('rad');

        // if current radiuses count is higher than mock count, it means the new area is smaller than before

        // if current radiuses count is smaller than mock count, it means the new area is bigger than before

        if ($radiuses->count() > $mock->count()) {
            $this->update([
                'radiuses' => $radiuses->take($mock->count()),
            ]);
        } else {
            $this->update([
                'radiuses' => $radiuses->union($mock),
            ]);
        }
    }

    private function mockRadiuses(): \Illuminate\Support\Collection
    {
        $radiuses = $this->getRadiusRange()->map(function ($item) {
            return [
                'rad' => $item,
                'time' => 0,
                'paid' => 0,
                'charged' => 0,
            ];
        });

        if ($this->charge_style == 'ROUTE') {
            $radiuses->push([
                'rad' => null,
                'time' => 0,
                'paid' => 0,
                'charged' => 0,
            ]);
        }

        return $radiuses;
    }

    private function getRadiusRange(): \Illuminate\Support\Collection
    {
        $range = collect(range(1, $this->getMaxRadius()));

        return $range;
    }

    private function getMaxRadius(): int
    {
        if (blank($this->area)) {
            return 0;
        }

        $result = DB::select(<<<EOT
                WITH location_geom AS (
                    SELECT
                        position::geometry
                    FROM
                        users
                    INNER JOIN
                        addresses
                    ON
                        users.id = addresses.user_id
                    WHERE users.id = $this->id
                ),
                area_geom AS (
                    SELECT
                        area::geometry
                    FROM
                        users
                    WHERE id = $this->id
                ),
                location AS (
                    SELECT
                        (ST_DumpPoints (location_geom.position)).*
                    FROM
                        location_geom
                ),
                area AS (
                    SELECT
                        (ST_DumpPoints (area_geom.area)).*
                    FROM
                        area_geom
                )
                SELECT
                    st_distancesphere (location.geom,
                        area.geom) AS distance
                FROM
                    location,
                    area
                ORDER BY
                    distance DESC limit 1;
        EOT);
    
        $maxRadius = $result[0]->distance;

        $maxRadius = (int) ceil($maxRadius / 1000);

        return $maxRadius;
    }

    public function getLastmileAttribute(): bool
    {
        return
            $this->charge_style == 'DAILY' ||
            $this->charge_style == 'PACKAGE' ||
            $this->charge_style == 'OPEN';
    }

    /**
     * @return (int|mixed|string)[]
     *
     * @psalm-return array{period?: 120|mixed, price?: string, markup?: string}
     */
    public function getChargeOptionsAttribute($value): array
    {
        if (! blank($value)) {
            $value = json_decode($value);
        }

        if ($this->charge_style == 'DAILY') {
            if (blank($value)) {
                return [
                    'period' => 120,
                    'price' => Helper::toMoney(0),
                    'markup' => Helper::toMoney(0),
                ];
            }

            return [
                'period' => $value->period,
                'price' => Helper::toMoney($value->price),
                'markup' => Helper::toMoney($value->markup),
            ];
        }

        if ($this->charge_style == 'PACKAGE') {
            if (blank($value)) {
                return [
                    'price' => Helper::toMoney(0),
                    'markup' => Helper::toMoney(0),
                ];
            }

            return [
                'price' => Helper::toMoney($value->price),
                'markup' => Helper::toMoney($value->markup),
            ];
        }

        if ($this->charge_style == 'OPEN') {
            if (blank($value)) {
                return [
                    'markup' => Helper::toMoney(0),
                ];
            }

            return [
                'markup' => Helper::toMoney($value->markup),
            ];
        }
        
        return [];
    }
}
