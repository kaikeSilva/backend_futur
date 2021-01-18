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
        //TODO: verificar retorno de detalhes de metas, itens estão retornando vazios
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'total_minutes' => $this->resource->total_minutes,
            'total_time' =>  to_string_time($this->resource->total_minutes),
            'percentage_complete' => $this->resource->percentage_complete,
            'late' => $this->whenLoaded('lateGoalItemsForToday') != new MissingValue() ? $this->resource->late : new MissingValue(),
            'days_limit' => $this->resource->days_limit,
            'courses' => CourseResource::collection($this->whenLoaded('courses')),
            'today_time_complete' => $this->resource->today_time_complete,
            'total_time_for_today' => $this->resource->total_time_for_today,
            'today_status' => !$this->resource->today_status,
            'late_goal_items_for_today' => GoalItemResource::collection($this->whenLoaded('lateGoalItemsForToday')),
            'goal_items_for_today' =>  GoalItemResource::collection($this->whenLoaded('goalItemsForToday')),
            'goal_items' => GoalItemResource::collection($this->whenLoaded('goalItems')),
            //TODO: melhorar query para itens do dia e tentar tranformar a função em uma relação
            'goal_items_per_day' => $this->whenLoaded('courses') != new MissingValue() ? GoalItemPerDayResource::collection($this->resource->goalItemsPerDay()) : new MissingValue()
        ];
    }
}
