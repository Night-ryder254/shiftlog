<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    /** @use HasFactory<\Database\Factories\ShiftFactory> */
    use HasFactory;

    public function branch()
{
    return $this->belongsTo(Branch::class);
}

public function shiftAssignments()
{
    return $this->hasMany(ShiftAssignment::class);
}
}
