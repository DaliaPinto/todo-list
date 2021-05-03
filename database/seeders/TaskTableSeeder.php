<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $task = new Task();
        $task->description = 'To do';
        $task->status = true;
        $task->save();

        $task = new Task();
        $task->description = 'In process';
        $task->status = false;
        $task->save();

        $task = new Task();
        $task->description = 'Completed';
        $task->status = true;
        $task->save();
    }
}
