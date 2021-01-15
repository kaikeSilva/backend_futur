<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
class GoalResource extends JsonResource
{
    private $loadItems;

    public function __construct($resource, $loadItems = false ) { 
        parent::__construct($resource);

        $this->loadItems = $loadItems;
    }
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
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'total_minutes' => $this->resource->total_minutes,
            'total_time' =>  to_string_time($this->resource->total_minutes),
            'percentage_complete' => $this->resource->percentage_complete,
            'days_limit' => $this->resource->days_limit,
            'courses' => CourseResource::collection($this->whenLoaded('courses')),
            'today_percentage_complete' => $this->loadItems ? $this->resource->Today_percentage_complete : new MissingValue(),
            'goal_items_for_today' =>  GoalItemResource::collection($this->whenLoaded('goalItemsForToday')),
            'goal_items' => GoalItemResource::collection($this->whenLoaded('goalItems')),
            'goal_items_per_day' => $this->loadItems ? GoalItemPerDayResource::collection($this->resource->goalItemsPerDay()) : new MissingValue()
        ];
    }
}
