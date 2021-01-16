<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/** Class GoalItem */
/** @package App */
/** @property int id */
/** @property string time_formatted */
/** @property string day_formatted */
/** @property string week_day_formatted */
/** @property int course_id */
/** @property int goal_id */
/** @property Course course */
/** @property int day */
/** @property int time */
/** @property int status */
/** @property Carbon created_at */
/** @property Carbon updated_at */
/** @property Carbon deleted_at */
class GoalItem extends Model
{
    use SoftDeletes;

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function getTimeFormattedAttribute() {
        return to_string_time($this->time);
    }

    public function getDayFormattedAttribute () {
        return to_string_date(new Carbon($this->day));
    }

    public function getWeekDayFormattedAttribute () {
        return week_day(new Carbon($this->day));
    }

    public function getLateAttribute () {
        $day = new Carbon($this->day);
        return $day->lt(today()->format('Y-m-d 00:00:00'));
    }
}
