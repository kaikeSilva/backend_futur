<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

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
            'time_formatted' => $this->resource->time_formatted,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'resource_place' => $this->resource->resource_place,
            'duration_minutes' => $this->resource->duration_minutes,
            'user' => new UserResource($this->whenLoaded('user')),
            'pivot' => new CourseGoalResource($this->whenPivotLoaded('course_goal', function () {
                return $this->resource->pivot;
            })) ?? new MissingValue()
        ];
    }
}
