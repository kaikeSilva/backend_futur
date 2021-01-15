<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/** Class User */
/** @package App */
/** @property int $id */
/** @property string $name */
/** @property string $email */
/** @property Carbon $email_verified_at */
/** @property string $password */
/** @property string $remember_token */
/** @property Carbon $created_at */
/** @property Carbon $updated_at */
/** @property Course[]|Collection $courses */
/** @property Carbon $deleted_at */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function courses() {
        return $this->hasMany(Course::class);
    }

    public function goals() {
        return $this->hasMany(Goal::class);
    }

    public function getTodayPercentageCompleteAttribute() {
        $goals = $this->goals;

        $done = 0;
        $todo = 0;
        $all = 0;

        foreach($goals as $item ) {
            $all += 1;
            $done += $item->today_percentage_complete;
        }

        return round($done*100/($all*100));
    }

    public function goalsEager() {
        return $this->hasMany(Goal::class)->with('courses');
    }
}
