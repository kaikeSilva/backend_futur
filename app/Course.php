<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Class Course */
/** @package App */
/** @property int $id*/
/** @property int $user_id*/
/** @property string $name*/
/** @property string $description*/
/** @property string $resource_place*/
/** @property string $time_formatted*/
/** @property int $duration_minutes*/
/** @property User $user*/
/** @property Carbon $created_at*/
/** @property Carbon $updated_at*/
/** @property Carbon $deleted_at*/
class Course extends Model
{
    // TODO: tornar curso em atividades e adicionar tipo de atividade: atividades de recurso financeiro e atividade de recurso de tempo
    use SoftDeletes;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function goals() {
        return $this->belongsToMany(Goal::class)
        ->using(CourseGoal::class)
        ->withPivot('id','done_minutes','deleted_at','status')
        ->withTimestamps();
    }

    public function getTimeFormattedAttribute() {
        return to_string_time($this->duration_minutes);
    }
}
