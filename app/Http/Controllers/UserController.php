<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Carbon\Carbon;

class UserController extends Controller
{   
    public function index(Request $request) {
        /** @var User $user */
        $filter =  json_decode($request->header('filters')) ;
        
        $date = today()->format('Y-m-d 00:00:00');
        
        if ($filter) {
            $date = Carbon::createFromFormat('d-m-Y',$filter);
            $date = $date->format('Y-m-d 00:00:00');
        }

        $user = auth()->user();
        $user->date = new Carbon($date);

        $user->load([
            'goals',
            'goals.goalItems'  => function ($q) use ($date) {
                $q->where("day", $date);
            },
            'goals.goalItems.course',
            'goals.lateGoalItemsForToday',
            'goals.lateGoalItemsForToday.course']);

        return new UserResource($user);
    }
}
