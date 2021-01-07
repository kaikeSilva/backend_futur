<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoalResource extends JsonResource
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
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'total_minutes' => $this->resource->total_minutes,
            'percentage_complete' => $this->resource->percentage_complete,
            'days_limit' => $this->resource->days_limit,
            'courses' => CourseResource::collection($this->whenLoaded('courses')),
            'goal_items' => GoalItemResource::collection($this->whenLoaded('goalItems'))
        ];
    }
}
