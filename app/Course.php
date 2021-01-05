<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Class Course */
/** @package App */
/** @property int $id*/
/** @property int $user_id*/
/** @property string $name*/
/** @property string $description*/
/** @property string $resource_place*/
/** @property int $duration_minutes*/
/** @property User $user*/
/** @property Carbon $created_at*/
/** @property Carbon $updated_at*/
/** @property Carbon $deleted_at*/
class Course extends Model
{
    use SoftDeletes;

    public function user() {
        return $this->belongsTo(User::class);
    }
}
