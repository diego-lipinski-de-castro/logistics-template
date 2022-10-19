<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{
    public static $wrap = 'delivery';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pub_id' => $this->pub_id,
            'cid' => $this->cid,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'driver_id' => $this->driver_id,
            'route_id' => $this->route_id,
            'receipt_id' => $this->receipt_id,
            'rad' => $this->rad,
            'time' => $this->time,
            'paid' => $this->paid,
            'return_fee_paid' => $this->return_fee_paid,
            'receipt_confirmation' => $this->receipt_confirmation,
            // 'private_text' => $this->private_text,
            'public_text' => $this->public_text,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'formatted_status' => $this->formatted_status,
            'formatted_paid' => $this->formatted_paid,
            'formatted_return_fee_paid' => $this->formatted_return_fee_paid,
            'formatted_total_paid' => $this->formatted_total_paid,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_updated_at' => $this->formatted_updated_at,
            'user' => $this->whenLoaded('user'),
            'steps' => $this->whenLoaded('steps'),
            'additional_info' => $this->additional_info,
            'lastmile' => $this->lastmile,
            'scheduled_at' => $this->scheduled_at,
            'formatted_scheduled_at' => $this->formatted_scheduled_at,
        ];
    }
}
