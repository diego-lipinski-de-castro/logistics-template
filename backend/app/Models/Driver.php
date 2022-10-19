<?php

namespace App\Models;

use App\Helper;
use App\Traits\HasFormattedDates;
use App\Traits\HasPubId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Propaganistas\LaravelPhone\PhoneNumber;

class Driver extends Authenticatable implements Auditable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasFormattedDates;
    use HasPubId;
    use \OwenIt\Auditing\Auditable;

    public const STATUSES = [
        'PENDING' => 'Pendente',
        'APPROVED' => 'Aprovado',
        'REJECTED' => 'Rejeitado',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'city_id',

        'first_name',
        'last_name',
        
        'email',
        'phone',
        
        'birthday',
        'cpf',

        'cnh',
        'vehicle_name',
        'vehicle_plate',

        'pix',

        'registered',
        'status',

        'banned_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'registered' => 'boolean',
        'banned_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_phone',
        'formatted_status',
        'banned',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($driver) {
            $driver->metadata()->create([]);
        });

        static::creating(function ($driver) {
            $fullname = Str::of("{$driver->first_name} {$driver->last_name}")->trim();

            if (! blank($fullname)) {
                $driver->setAttribute('full_name', $fullname);
            }
        });

        static::updating(function ($driver) {
            $fullname = Str::of("{$driver->first_name} {$driver->last_name}")->trim();

            if (! blank($fullname)) {
                $driver->setAttribute('full_name', $fullname);
            }
        });

        static::deleting(function ($driver) {
            $driver->metadata()->delete();
            $driver->documents()->delete();
        });
    }

    /**
     * @psalm-return array{include_external_user_ids: mixed}
     */
    public function routeNotificationForOneSignal(): array
    {
        return ['include_external_user_ids' => $this->pub_id];
    }

    public function scopeIdle($query): void
    {
        $query->whereDoesntHave('deliveries', function ($q) {
            $q->ofStatus(['ACCEPTED', 'COLLECTING', 'DELIVERING', 'RETURNING']);
        });
    }

    public function scopeNotBanned($query)
    {
        return $query->whereNull('banned_at');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'APPROVED');
    }

    public function scopeOfStatus($query, $value)
    {
        return $query->whereHas('metadata', function ($q) use ($value) {
            $q->where('driver_metadata.status', $value);
        });
    }

    public static function scopeOfCity($query, int|array $value)
    {
        if (is_array($value)) {
            return $query->whereIn('city_id', $value);
        }

        return $query->where('city_id', $value);
    }

    public function scopeOfCities($query, int|array $value)
    {
        return self::scopeOfCity($query, $value);
    }

    public function scopeOfUser($query, $value)
    {
        return $query->whereHas('users', function ($q) use ($value) {
            $q->where('users.id', $value);
        });
    }

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function metadata(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DriverMetadata::class);
    }

    public function documents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function deliveries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
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

    public function getPhoneAttribute(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return str_replace('+55', '', $value);
    }

    public function getFormattedPhoneAttribute(): ?string
    {
        if (blank($this->phone)) {
            return null;
        }

        return PhoneNumber::make($this->raw_phone, 'BR')->formatNational();
    }

    public function getRawPhoneAttribute(): string
    {
        return $this->attributes['phone'];
    }

    public function setPhoneAttribute(string $value): void
    {
        $this->attributes['phone'] = Helper::toPhone($value);
    }

    public function setFirstNameAttribute(?string $value): void
    {
        if (blank($value)) {
            return;
        }

        $this->attributes['first_name'] = Str::ucfirst($value);
    }

    public function setLastNameAttribute(?string $value): void
    {
        if (blank($value)) {
            return;
        }

        $this->attributes['last_name'] = Str::ucfirst($value);
    }
}
