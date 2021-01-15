<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/** Class GoalItem */
/** @package App */
/** @property int id */
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
        $hour = floor($this->time/60);
        $minutes = $this->time%60;

        if ($hour == 0) return $minutes." min"; 
        return $hour."h ".$minutes." min";
    }
}
