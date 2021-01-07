<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Carbon\Carbon;

/** Class CourseGoal */
/** @package App */
/** @property int id */
/** @property int course_id */
/** @property int goal_id */
/** @property int done_minutes */
/** @property Carbon created_at */
/** @property Carbon updated_at */
class CourseGoal extends Pivot
{
    use softDeletes;

    protected $appends = [
        'test'
    ];

    public function getTestAttribute() {
        return "teste";
    }
}
