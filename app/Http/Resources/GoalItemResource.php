<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoalItemResource extends JsonResource
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
            'goal_id' => $this->resource->goal_id,
            'day_formatted' => $this->resource->day_formatted,
            'week_day_formatted' => $this->resource->week_day_formatted,
            'late' => $this->resource->late,
            'comments' => $this->resource->comments,
            'day' => $this->resource->day,
            'time' => $this->resource->time,
            'time_formatted' => $this->resource->time_formatted,
            'status' => $this->resource->status,
            'course' => new CourseResource($this->whenLoaded('course')),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at
        ];
    }
}
