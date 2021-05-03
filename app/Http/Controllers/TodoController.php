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
                'message' => 'Some fields are wrong',
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
                'message' => 'Item has been added successfully',
                'error' => false,
                'item' => $todo->load(['user', 'task'])
            ]);
        }

        return collect([
            'status' => 1,
            'message' => 'There was a mistake adding the item',
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
                'message' => 'Some fields are wrong',
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
                'message' => 'Item has been updated successfully'
            ]);
        }

        return collect([
            'status' => 1,
            'error' => true,
            'message' => 'There was a mistake updating the item',
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
                'message' => 'Item has been destroyed successfully'
            ]);

        return collect([
            'status' => 1,
            'error' => true,
            'item' => $todo,
            'message' => 'There was a mistake destroying the item',
        ]);
    }
}
