<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    use HasFactory;
    protected $table = 'attendance_settings';
    protected $primaryKey = 'attendance_settings_id';
}
