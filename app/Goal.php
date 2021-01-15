<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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

    public function courses() {
        return $this->belongsToMany(Course::class)
        ->using(CourseGoal::class)
        ->withPivot('id','done_minutes','deleted_at','status')
        ->withTimestamps();
    }

    public function goalItems() {
        //dd(today()->addDay()->format('Y-m-d 00:00:00'));
        return $this->hasMany(GoalItem::class);
    }

    public function goalItemsForToday() {
        //dd(today()->addDay()->format('Y-m-d 00:00:00'));
        return $this->hasMany(GoalItem::class)->where('day',today()->addDay()->format('Y-m-d 00:00:00') );
    }

    public function goalItemsPerDay() {
        
        $initialDate = new Carbon($this->created_at);
        $period = CarbonPeriod::create( $initialDate->addDay(), now()->addDays($this->days_limit));
        $dates = $period->toArray();
        $objectItems = collect();
        foreach ($dates as $day) {
            $items = GoalItem::where([
                ['goal_id',$this->id],
                ['day',$day]
            ])->get()->load('course');

            $item = new stdClass();
            $item->day = $day;
            $item->items = $items;
        
            $objectItems->push($item);
        }

        return $objectItems;
    }

}
