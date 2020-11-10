<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;

use JWTAuth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function response($status, $success, $message = '', array $data = [], array $errors = [])
    {
        return response()->json([
            'success' => $success, 'message' => $message, 'data' => $data, 'errors' => $errors
        ], $status);
    }

    public function server_error_response($e)
    {
        if (env('APP_ENV') != 'production') {
            echo $e->getMessage();
            die();
        }
        return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, false, 'Internal Server Error');
    }

    public function auth_user()
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (!$user) return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, false, 'Authorization is required');
        return $user;
    }

    public function map_data($to, array $data)
    {
        foreach ($data as $key => $value) {
            $to[$key] = $value;
        }
        return $to;
    }

    public function get_data($model, array $query_arr = [])
    {
        return $model::where($this->merge_query_data($query_arr))->get();
    }

    public function check_if_exist($model, array $query_arr)
    {
        if (count($query_arr) == 0) {
            echo 'check_if_exist query array is empty';
            die();
        }
        return $model::where($this->merge_query_data($query_arr))->exists();
    }

    public function merge_query_data($query_arr)
    {
        $query = [
            'business_id' => $this->auth_user()->business_id,
            'isDeleted' => false
        ];
        if (count($query_arr) > 0) {
            $query =  array_merge($query, $query_arr);
        }
        return $query;
    }

    public function check_permission($permission)
    {
        $user = $this->auth_user();

        // get user role
        $role_user_id = DB::table('role_user')
            ->where(['business_id' => $user->business_id, 'user_id' => $user->user_id,  'isAvailable' => true, 'isDeleted' => false])
            ->get('role_id')->first();

        // Permission Module
        $permission_role = DB::table('permission_role')
            ->join('permission_module', 'permission_role.permission_module_id', '=', 'permission_module.permission_module_id')
            ->select('permission_module.permission_module_name', 'permission_role.view', 'permission_role.add', 'permission_role.edit', 'permission_role.delete')
            ->where(['business_id' => $user->business_id, 'role_id' => $role_user_id->role_id, 'isAvailable' => true, 'isDeleted' => false])
            ->get()->toArray();

        $permissionArray = [];
        foreach ($permission_role as $key) {
            $permissionArray["$key->permission_module_name[view]"] = $key->view;
            $permissionArray["$key->permission_module_name[add]"] = $key->add;
            $permissionArray["$key->permission_module_name[edit]"] = $key->edit;
            $permissionArray["$key->permission_module_name[delete]"] = $key->delete;
        }

        if (array_key_exists($permission, $permissionArray) && $permissionArray[$permission]) return true;
        return false;
    }
}
