<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    protected $fillable = [
        'description',
        'is_completed',
        'completed_at',
        'task_id',
        'user_id'
    ];

    public static $rules = [
        'description' => 'required|string|max:194',
        'is_completed' => 'nullable|boolean',
        'completed_at' => 'nullable',
        'task_id' => 'exists:tasks,id|required',
        'user_id' => 'exists:users,id|nullable'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
