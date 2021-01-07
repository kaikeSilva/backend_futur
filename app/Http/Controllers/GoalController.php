<?php

namespace App\Http\Controllers;

use App\Goal;
use App\Course;
use App\GoalItem;
use Illuminate\Http\Request;
use App\Http\Resources\GoalResource;
use Carbon\CarbonPeriod;

class GoalController extends Controller
{
    public function index()
    {
        return GoalResource::collection(auth()->user()->goalsEager);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->toArray();
        $messages = [
            'required' => ':attribute não pode ser vazio',
        ];
        
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
            'days_limit' => 'required',
            'courses' => 'required|exists:courses,id'
        ],$messages);

        extract($request->toArray());

        /** @var Goal $course*/
        $goal = new Goal();

        $total = 0;
        foreach ($courses as $id) {
            $course = Course::find($id);
            $total += $course->duration_minutes;
        }

        $goal->user_id = auth()->user()->id;
        $goal->title = $title;
        $goal->description = $description;
        $goal->days_limit = $days_limit;
        $goal->total_minutes = $total;
        $goal->percentage_complete = 0;


        $goal->save();
        
        $goalItems = [];
        foreach ($courses as $id) {
            $course = Course::find($id);

            $period = CarbonPeriod::create(now()->addDay(), now()->addDays($days_limit+1));
            $dates = $period->toArray();
            $timePerDay = ceil($course->duration_minutes/$days_limit);
            $acumulatedTime = 0;
            
            for ($day=0; $day < $days_limit; $day++) {
                /** @var GoalItem*/
                $goalItem = new GoalItem();
                
                $goalItem->course_id = $id;
                $goalItem->goal_id = $goal->id;
                $goalItem->day = $dates[$day];
                $goalItem->status = 0;
                
                if($timePerDay < ($course->duration_minutes - $acumulatedTime)) {
                    $goalItem->time = $timePerDay;
                    $acumulatedTime += $timePerDay;
                    $goalItem->save();
                    $goalItems[] = $goalItem;
                } else {
                    $goalItem->time = $course->duration_minutes - $acumulatedTime;
                    $goalItem->save();
                    $goalItems[] = $goalItem;
                    break;
                }
                
            }
        }
        
        $goal->courses()->attach($courses);
        $goal->load(['courses','goalItems']);

        return new GoalResource($goal);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id, Request $request)
    {
        try {
            $goal = Goal::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Meta não encontrada!'], 404);
        }
        $goal->load('courses');
        return new GoalResource($goal);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $messages = [
            'required' => ':attribute não pode ser vazio',
        ];
        
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
        ],$messages);

        try {
            $goal = Goal::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Curso não encontrado!'], 404);
        }

        extract($request->toArray());

        $goal->title = $title;
        $goal->description = $description;

        $goal->save();
        return new GoalResource($goal);
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $goal = Goal::findOrFail($id);
            $goal->courses()->detach();
            $goal->goalItems()->delete();
            $goal->delete();
            return response()->json(['message'=>'Meta deletada com sucesso'],200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Meta não encontrada!'], 404);
        }   
    }
}
