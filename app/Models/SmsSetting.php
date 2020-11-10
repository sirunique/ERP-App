<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    use HasFactory;
    protected $table = 'sms_settings';
    protected $primaryKey = 'sms_setting_id';
}
