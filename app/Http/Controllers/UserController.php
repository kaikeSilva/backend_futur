<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{   // TODO: Adicionar filtro para datas e retirar logica de dia da model 
    public function index() {
        /** @var User $user */

        $date = today()->format('Y-m-d 00:00:00');

        $user = auth()->user();
        $user->load([
            'courses',
            'goals',
            'goals.goalItemsForToday'  => function ($q) use ($date) {
                $q->where("day", $date);
            },
            'goals.goalItemsForToday.course',
            'goals.lateGoalItemsForToday' => function ($q) use ($date) {
                $q->where('day','<',$date);
            },
            'goals.lateGoalItemsForToday.course']);

        return new UserResource($user);
    }
}
