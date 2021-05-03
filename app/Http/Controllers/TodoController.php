<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Todo::$rules);

        if ($validator->fails())
        {
            return collect([
                'status' => 1,
                'errors' => $validator->errors(),
                'message' => 'Hubo algunos errores al momento de validar los campos.',
                'error' => true
            ]);
        }

        $todo = new Todo();
        $todo->description = $request['description'];
        $todo->is_completed = false;
        $todo->user_id = 1;
        $todo->task_id = Task::where('description', 'like', '%To do%')->first()->id;

        if ($todo->save())
        {
            return collect([
                'status' => 0,
                'message' => 'La tarea se añadió exitosamente',
                'error' => false,
                'item' => $todo->load(['user', 'task'])
            ]);
        }

        return collect([
            'status' => 1,
            'message' => 'Hubo un error al crear la tarea',
            'error' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        $validator = Validator::make($request->all(), Todo::$rules);

        $todo_request = $request->all();

        if ($validator->fails())
        {
            return collect([
                'status' => 1,
                'errors' => $validator->errors(),
                'message' => 'Hubo algunos errores al momento de validar los campos.',
                'error' => true
            ]);
        }

        if ($todo_request['is_completed'])
        {
            $todo_request['task_id'] = Task::where('description', 'like', '%Completed%')->first()->id;
            $todo_request['completed_at'] = Carbon::now()->toDateTimeString();
        }
        else
            $todo_request['task_id'] = Task::where('description', 'like', '%To do%')->first()->id;

        if($todo->update($todo_request))
        {
            return collect([
                'status' => 0,
                'error' => false,
                'item' => $todo->load(['user', 'task']),
                'message' => 'La tarea se ha actualizado exitosamente'
            ]);
        }

        return collect([
            'status' => 1,
            'error' => true,
            'message' => 'Hubo un error al actualizar la tarea'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        if ($todo->destroy($todo->id))
            return collect([
                'status' => 0,
                'error' => false,
                'message' => 'La tarea ha sido eliminada'
            ]);

        return collect([
            'status' => 1,
            'error' => true,
            'item' => $todo,
            'message' => 'Hubo un error al eliminar la tarea'
        ]);
    }
}
