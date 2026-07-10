<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceRecordFactory> */
    use HasFactory;

    protected $fillable = ['shift_assignment_id', 'clock_in', 'clock_out', 'status'];
    public function shiftAssignment()
{
    return $this->belongsTo(ShiftAssignment::class);
}
}
