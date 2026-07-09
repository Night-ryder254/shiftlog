<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory;

    public function branch()
{
    return $this->belongsTo(Branch::class);
}

public function employees()
{
    return $this->hasMany(Employee::class);
}
}
