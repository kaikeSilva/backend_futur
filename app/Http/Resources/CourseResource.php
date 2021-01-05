<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'resource_place' => $this->resource->resource_place,
            'duration_minutes' => $this->resource->duration_minutes,
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
