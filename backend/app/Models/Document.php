<?php

namespace App\Models;

use App\Traits\HasFormattedDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mimey\MimeTypes;

class Document extends Model
{
    use HasFactory;
    use HasFormattedDates;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'driver_id',

        'folder',

        'name',
        'type',

        'path',
        'url',
    ];

    protected $appends = [
        'full_url',
        'mime',
        'mimey',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (Document $document) {
            if (empty($document->{$document->getKeyName()})) {
                $document->{$document->getKeyName()} = (string) Str::uuid();
            }
        });

        static::deleted(function (Document $document) {
            Storage::delete($document->path);
        });
    }

    public function getFullUrlAttribute(): ?string
    {
        if (! auth()->check()) {
            return null;
        }

        if (auth()->user() instanceof Developer) {
            return null;
        }

        if (auth()->user() instanceof Driver) {
            return route('driver.showDocument', $this->id);
        }

        return null;
    }

    public function getMimeAttribute(): ?string
    {
        return (new MimeTypes)->getMimeType($this->type);
    }

    public function getMimeyAttribute(): ?string
    {
        if (blank($this->mime)) {
            return null;
        }

        return explode('/', $this->mime)[0];
    }
}
