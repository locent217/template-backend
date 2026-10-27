<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DepartmentUserTypePermission extends Model
{
    use HasUuids;

    protected $fillable = ['department_id', 'user_type_id', 'permission_id'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
