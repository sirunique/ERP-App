<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    use HasFactory;
    protected $table = 'permission_role';
    protected $primaryKey = 'permission_role_id';
    protected $fillable = [
        'permission_role_id', 'business_id', 'role_id',
        'permission_module_id', 'view', 'add', 'edit', 'delete', 'isAvailable', 'isDeleted'
    ];
}
