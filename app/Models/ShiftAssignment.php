<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftAssignment extends Model
{
    /** @use HasFactory<\Database\Factories\ShiftAssignmentFactory> */
    use HasFactory;

    public function employee()
{
    return $this->belongsTo(Employee::class);
}

public function shift()
{
    return $this->belongsTo(Shift::class);
}

public function attendanceRecord()
{
    return $this->hasOne(AttendanceRecord::class);
}
}
