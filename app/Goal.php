<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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
        return $this->hasMany(GoalItem::class);
    }
}
