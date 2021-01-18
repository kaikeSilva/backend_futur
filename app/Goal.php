<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Resources\MissingValue;
use stdClass;

/** Class Goal */
/** @package App */
/** @property int id */
/** @property int user_id */
/** @property string title */
/** @property string description */
/** @property int total_minutes */
/** @property float percentage_complete */
/** @property int days_limit */
/** @property GoalItem[]|Collection goalItems */
/** @property Course[]|Collection courses */
/** @property Carbon created_at */
/** @property Carbon updated_at */
/** @property Carbon deleted_at */
class Goal extends Model
{
    use SoftDeletes;
    
    const STATUS_DONE = 1;
    const STATUS_TODO = 0;

    public function courses() {
        return $this->belongsToMany(Course::class)
        ->using(CourseGoal::class)
        ->withPivot('id','done_minutes','deleted_at','status')
        ->withTimestamps();
    }

    public function goalItems() {
        return $this->hasMany(GoalItem::class);
    }

    public function lateGoalItemsForToday() {
        return $this->hasMany(GoalItem::class)
        ->where('day','<',today()->format('Y-m-d 00:00:00'))
        ->where('status',self::STATUS_TODO);
    }

    public function getTodayStatusAttribute() {
        $all = $this->total_time_for_today;
        $done = $this->today_time_complete;
        return $done < $all;
    }

    public function getTodayTimeCompleteAttribute() {
        return $this->goalItems
        ->where('status',self::STATUS_DONE)->sum('time');    
    }

    public function getTotalTimeForTodayAttribute() {
        return $this->goalItems
        ->sum('time');    
    }

    public function goalItemsPerDay() {
        $items = GoalItem::where('goal_id',$this->id )
        ->with('course')
        ->get()
        ->groupBy('day');

        $objectItems = collect();
        foreach ($items as $key => $value) {

            $item = new stdClass();
            $item->day = $key;
            $item->items = $value;
        
            $objectItems->push($item);
        }

        return $objectItems;
    }

    public function getLateAttribute() {
        return $this->lateGoalItemsForToday->count() > 0;
    }

}
