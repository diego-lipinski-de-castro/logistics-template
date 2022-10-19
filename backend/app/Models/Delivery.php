<?php

namespace App\Models;

use App\Helper;
use App\Traits\HasFormattedDates;
use App\Traits\HasPubId;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;

class Delivery extends Model implements Auditable
{
    use HasFactory;
    use HasFormattedDates;
    use HasPubId;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    public const STATUSES = [
        'WAITING' => 'Aguardando',
        'PENDING' => 'Pendente',
        'ACCEPTED' => 'Aceita',
        'COLLECTING' => 'Coletando',
        'DELIVERING' => 'Entregando',
        'COMPLETED' => 'Entregue',
        'CANCELED' => 'Cancelada',
        'RETURNING' => 'Retornando',
        'CONFIRMED' => 'Confirmada',
        'NOT_DELIVERED' => 'Não entregue',
    ];

    public const RECEIPT_CONFIRMATION = [
        'DISABLED' => 'Desativado (não irá aparecer a opção de confirmação)',
        'OPTIONAL' => 'Opcional (irá aparecer a opção mas não será obrigatório)',
        'REQUIRED_PARTIAL' => 'Obrigatório (irá aparecer a opção e será obrigatório)',
        'REQUIRED' => 'Obrigatório com foto (irá aparecer a opção e será obrigatório anexar foto)',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'pub_id',
            
        'status',
        
        'user_id',
        'driver_id',
    
        'rad',
        'time',
        'paid',
        'charged',
        
        'return_fee_paid',
        'return_fee_charged',

        'receipt_confirmation',
        
        'private_text',
        'public_text',

        'customer_name',
        'customer_phone',

        'pending_at',
        'accepted_at',
        'delivered_at',
        'elapsed_time',

        'additional_info',

        'charge_style',

        'scheduled_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        //
    ];

    protected $appends = [
        'formatted_status',
        'formatted_return_fee_paid',
        'formatted_return_fee_charged',
        'formatted_paid',
        'formatted_charged',
        'total_paid',
        'formatted_total_paid',
        'total_charged',
        'formatted_total_charged',
        'formatted_pending_at',
        'formatted_accepted_at',
        'formatted_delivered_at',
        'formatted_created_at_time',
        'formatted_updated_at_time',
        'formatted_report_created_at',
        'lastmile',
        'formatted_scheduled_at',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($delivery) {
            if (! blank($delivery->cid)) {
                return;
            }

            do {
                $delivery->setAttribute('cid', Str::upper(Str::random(6)));
            } while (self::where('cid', $delivery->cid)->exists());
        });
    }

    public function scopeNotRefused($query, $driverId)
    {
        return $query->whereDoesntHave('shots', function ($subquery) use ($driverId) {
            $subquery->where('driver_id', $driverId)->where('action', 'REFUSED');
        });
    }

    public static function scopeOfCity($query, int|array $value = [])
    {
        if (is_array($value)) {
            return $query->whereHas('user', function ($q) use ($value) {
                $q->ofCities($value);
            });
        }

        return $query->whereHas('user', function ($q) use ($value) {
            $q->ofCity($value);
        });
    }

    public function scopeOfCities($query, int|array $value = [])
    {
        return self::scopeOfCity($query, $value);
    }

    public function scopeOfUser($query, int $userId)
    {
        return $query->where('deliveries.user_id', $userId);
    }

    public function scopeOfDriver($query, int|null $driverId)
    {
        return $query->where('deliveries.driver_id', $driverId);
    }

    public function scopeOfStatus($query, string|array $value = [])
    {
        if (is_array($value)) {
            return $query->whereIn('deliveries.status', $value);
        }

        return $query->where('deliveries.status', $value);
    }

    public function scopeOfStatuses($query, string|array $value = [])
    {
        return self::scopeOfStatus($query, $value);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function receipt(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Receipt::class);
    }

    public function route(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Route::class);
    }

    public function steps(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Step::class)->orderByDesc('prev_id');
    }

    public function shots(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Shot::class);
    }

    public function getFormattedStatusAttribute(): string
    {
        return self::STATUSES[$this->status];
    }

    public function getFormattedReturnFeePaidAttribute(): ?string
    {
        if (blank($this->return_fee_paid)) {
            return null;
        }

        return Helper::toMoney($this->return_fee_paid);
    }

    public function getFormattedReturnFeeChargedAttribute(): ?string
    {
        if (blank($this->return_fee_charged)) {
            return null;
        }

        return Helper::toMoney($this->return_fee_charged);
    }

    public function getFormattedPaidAttribute(): string
    {
        if (blank($this->paid)) {
            return null;
        }

        return Helper::toMoney($this->paid);
    }

    public function getFormattedChargedAttribute(): string
    {
        if (blank($this->charged)) {
            return null;
        }

        return Helper::toMoney($this->charged);
    }

    public function getTotalPaidAttribute(): int
    {
        return ($this->paid ?? 0) + ($this->return_fee_paid ?? 0);
    }

    public function getFormattedTotalPaidAttribute(): string
    {
        return Helper::toMoney($this->total_paid);
    }

    public function getTotalChargedAttribute(): int
    {
        if ($this->lastmile) {
            // the charged value is the markup value (user charge options)
            return ($this->paid ?? 0) + ($this->charged ?? 0);
        }

        return ($this->charged ?? 0) + ($this->return_fee_charged ?? 0);
    }

    public function getFormattedTotalChargedAttribute(): string
    {
        return Helper::toMoney($this->total_charged);
    }

    public function getPendingAtAttribute($date): ?Carbon
    {
        if (blank($date)) {
            return null;
        }

        return Carbon::parse($date);
    }

    public function getFormattedPendingAtAttribute()
    {
        if (blank($this->pending_at)) {
            return null;
        }

        return $this->pending_at->format('H:i:s');
    }

    public function getAcceptedAtAttribute($date): ?Carbon
    {
        if (blank($date)) {
            return null;
        }

        return Carbon::parse($date);
    }

    public function getFormattedAcceptedAtAttribute()
    {
        if (blank($this->accepted_at)) {
            return null;
        }

        return $this->accepted_at->format('H:i:s');
    }

    public function getDeliveredAtAttribute($date): ?Carbon
    {
        if (blank($date)) {
            return null;
        }

        return Carbon::parse($date);
    }

    public function getFormattedDeliveredAtAttribute()
    {
        if (blank($this->delivered_at)) {
            return null;
        }

        return $this->delivered_at->format('H:i:s');
    }

    public function getFormattedCreatedAtTimeAttribute()
    {
        if (blank($this->created_at)) {
            return null;
        }

        return $this->created_at->format('H:i');
    }

    public function getFormattedUpdatedAtTimeAttribute()
    {
        if (blank($this->updated_at)) {
            return null;
        }

        return $this->updated_at->format('H:i');
    }

    public function getFormattedReportCreatedAtAttribute()
    {
        if (blank($this->created_at)) {
            return null;
        }

        return $this->created_at->format('d/m/Y H:i:s');
    }

    public function getLastmileAttribute(): bool
    {
        return
            $this->charge_style == 'DAILY' ||
            $this->charge_style == 'PACKAGE' ||
            $this->charge_style == 'OPEN';
    }

    public function getFormattedScheduledAtAttribute(): string|null
    {
        if (blank($this->scheduled_at)) {
            return null;
        }

        return Carbon::parse($this->scheduled_at)->format('d/m \à\s H:i');
    }
}
