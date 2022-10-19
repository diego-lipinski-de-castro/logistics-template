<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use OwenIt\Auditing\Contracts\Audit as ContractsAudit;

class Audit extends Model implements ContractsAudit
{
    use \OwenIt\Auditing\Audit;

    protected $guarded = [];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    public function auditable()
    {
        return $this->morphTo()->withTrashed();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveData(): array
    {
        $morphPrefix = Config::get('audit.user.morph_prefix', 'user');

        $this->data = [
            'audit_id' => $this->id,
            'audit_event' => $this->event,
            'audit_event_name' => __('audits/events.' . $this->event),
            'audit_url' => $this->url,
            'audit_ip_address' => $this->ip_address,
            'audit_user_agent' => $this->user_agent,
            'audit_tags' => $this->tags,
            'audit_created_at' => $this->serializeDate($this->created_at),
            'audit_updated_at' => $this->serializeDate($this->updated_at),
            'user_id' => $this->getAttribute($morphPrefix.'_id'),
            'user_type' => $this->getAttribute($morphPrefix.'_type'),
        ];

        if ($this->user) {
            foreach ($this->user->getArrayableAttributes() as $attribute => $value) {
                $this->data['user_'.$attribute] = $value;
            }
        }

        $this->metadata = array_keys($this->data);

        foreach ($this->new_values as $key => $value) {
            $this->data['new_'.$key] = $value;
        }

        foreach ($this->old_values as $key => $value) {
            $this->data['old_'.$key] = $value;
        }

        $this->modified = array_diff_key(array_keys($this->data), $this->metadata);

        return $this->data;
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('d/m/Y H:i:s');
    }
}
