<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CourseResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CourseResource::collection(auth()->user()->courses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'required' => ':attribute não pode ser vazio',
        ];
        
        $this->validate($request,[
            'name' => 'required',
            'duration_hours' => 'required',
            'duration_minutes' => 'required',
            'description' => 'required',
            'resource_place' => 'required',
        ],$messages);

        extract($request->toArray());

        /** @var Course $course*/
        $course = new Course();
        $course->user_id = auth()->user()->id;
        $course->name = $name;
        $course->description = $description;
        $course->resource_place = $resource_place;
        $course->duration_minutes = $duration_minutes + $duration_hours*60;

        $course->save();

        return new CourseResource($course);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id, Request $request)
    {
        try {
            $course = Course::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Curso não encontrado!'], 404);
        }
        //$course->load('user');
        return new CourseResource($course);
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
        
        // Adicionar horas e minutos na atualização pode gerar inconsistencia
        // Remover isso depois
        $this->validate($request,[
            'name' => 'required',
            'description' => 'required',
            'resource_place' => 'required',
            'duration_hours' => 'required',
            'duration_minutes' => 'required',
        ],$messages);

        try {
            $course = Course::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Curso não encontrado!'], 404);
        }

        extract($request->toArray());

        $course->name = $name;
        $course->description = $description;
        $course->resource_place = $resource_place;
        $course->duration_minutes = $duration_minutes + $duration_hours*60;

        $course->save();
        return new CourseResource($course);
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $course = Course::findOrFail($id);

            if ($course->goals()->count() > 0) {
                throw new \Exception('Este curso possui metas e não pode ser deletado');
            }

            $course->delete();
            return response()->json(['message'=>'Curso deletado com sucesso'],200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 404);
        }   
    }
}
