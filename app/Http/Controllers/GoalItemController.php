<?php

namespace App\Http\Controllers;

use App\GoalItem;
use App\Goal;
use App\Http\Resources\GoalItemResource;
use App\Http\Resources\GoalResource;
use Illuminate\Http\Request;

class GoalItemController extends Controller
{
        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try {
            $goalItem = GoalItem::findOrFail($id);
            /** @var Goal $goal */
            $goal = Goal::findOrFail($goalItem->goal_id);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Item de meta não encontrado!'], 404);
        }

        $goalItem->status = $goalItem->status ? 0 : 1;
        $goalItem->save();
        
        $pivot = $goal->courses()->wherePivot('course_id', $goalItem->course_id)->first()->pivot;
        $updatedTime =  $goalItem->status ? $pivot->done_minutes + $goalItem->time : $pivot->done_minutes - $goalItem->time ;

        $goal->courses()->updateExistingPivot($goalItem->course_id, [
            'done_minutes' => $updatedTime
        ]);
        
        $goalItem->load(['course']);
        return new GoalItemResource($goalItem);
    }
}
