<?php

namespace App\Traits;

trait HasFormattedDates
{
    public function initializeHasFormattedDates(): void
    {
        $this->append('formatted_created_at');
        $this->append('formatted_updated_at');
    }

    public function getFormattedCreatedAtAttribute()
    {
        if (blank($this->created_at)) {
            return null;
        }

        return $this->created_at->format('H:i d/m/Y');
    }

    public function getFormattedUpdatedAtAttribute()
    {
        if (blank($this->updated_at)) {
            return null;
        }

        return $this->updated_at->format('H:i d/m/Y');
    }
}
