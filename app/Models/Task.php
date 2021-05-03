<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $fillable = [
        'description',
        'status',
    ];

    public static $rules = [
        'description' => 'required|string|max:194',
        'status' => 'required|boolean',
    ];

    public function todos()
    {
        return $this->hasMany(Todo::class);
    }
}
