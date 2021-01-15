<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class UserResource extends JsonResource
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
            'week_day' => $this->resource->week_day, 
            'today' => $this->resource->today,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'today_percentage_complete' =>$this->whenLoaded('goals') ? $this->resource->today_percentage_complete : new MissingValue(),
            'courses' => CourseResource::collection($this->whenLoaded('courses')),
            'goals' => GoalResource::collection($this->whenLoaded('goals')),
        ];
    }
}
