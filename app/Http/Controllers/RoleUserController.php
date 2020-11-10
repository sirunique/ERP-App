<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\RoleUser;
use Illuminate\Validation\Rule;

class RoleUserController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Role[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $roleUser = $this->get_data(RoleUser::class);
            return $this->response(Response::HTTP_OK, true, 'Role User', ['Role User' => $roleUser]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function store(Request $request)
    {
        $check = $this->check_permission('Role[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'role_id' => [
                'required', 'integer',
                Rule::exists('roles')->where(function ($query) {
                    $query->where(['isAvailable' => 1, 'business_id' => $this->auth_user()->business_id]);
                })
            ],
            'user_id' => [
                'required', 'integer',
                Rule::exists('users')->where(function ($query) {
                    $query->where(['isActive' => 1, 'business_id' => $this->auth_user()->business_id]);
                })
            ],
        ]);

        // check if user already have a role (user_id) in roleuser
        $checkrole = $this->check_if_exist(RoleUser::class, ['business_id' => $this->auth_user()->business_id, 'isAvailable' => 1, 'user_id' => $request->user_id]);
        if ($checkrole) {
            return $this->response(400, false, 'User Already Have a Role');
        }

        try {
            // check if exist
            $roleUser = $this->check_if_exist(RoleUser::class, $request->all());

            if ($roleUser) {
                return $this->response(400, false, 'Role User Already Exist');
            }

            // save 
            $roleUser = new RoleUser();
            $roleUser->business_id = $this->auth_user()->business_id;
            $roleUser->user_id = $this->auth_user()->user_id;
            $roleUser = $this->map_data($roleUser, $request->all());
            $roleUser->save();

            return $this->response(Response::HTTP_OK, true, 'Role Created', ['Role' => $roleUser]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function show(Request $request)
    {
        $check = $this->check_permission('Role[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $roleUser = $this->get_data(RoleUser::class, ['role_user_id' => $this->get_req_id($request)]);
            if (count($roleUser) == 0) {
                return $this->response(400, false, 'Role Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Role User', ['Role User' => $roleUser]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function update(Request $request)
    {
        $check = $this->check_permission('Role[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        // validate data
        $request->validate([
            'role_id' => [
                'required', 'integer',
                Rule::exists('roles')->where(function ($query) {
                    $query->where(['isAvailable' => 1, 'business_id' => $this->auth_user()->business_id]);
                })
            ],
            'user_id' => [
                'required', 'integer',
                Rule::exists('users')->where(function ($query) {
                    $query->where(['isActive' => 1, 'business_id' => $this->auth_user()->business_id]);
                })
            ],
            'isAvailable' => 'required|boolean'
        ]);

        try {
            // get Role
            $roleUser = $this->get_data(RoleUser::class, ['role_id' => $this->get_req_id($request)]);
            if (count($roleUser) == 0) {
                return $this->response(400, false, 'Role Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(RoleUser::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'Role Already Exist');
            }

            // save
            $roleUser = $roleUser->first();
            $roleUser = $this->map_data($roleUser, $request->all());
            $roleUser->save();

            return $this->response(Response::HTTP_OK, true, 'Role Updated', ['Role' => $roleUser]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function destroy(Request $request)
    {
        $check = $this->check_permission('Role[delete]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            // get category
            $roleUser = $this->get_data(RoleUser::class, ['role_user_id' => $this->get_req_id($request)]);
            if (count($roleUser) == 0) {
                return $this->response(400, false, 'Role Not Found');
            }

            // update 
            $roleUser = $roleUser->first();
            $roleUser->isAvailable = false;
            $roleUser->isDeleted = true;
            $roleUser->save();

            return $this->response(Response::HTTP_OK, true, 'Role Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function get_req_id(Request $request)
    {
        $roleUserId = $request->role_user_id;
        if (!$roleUserId) return $this->response(400, false, 'Role ID Not Found');
        return $roleUserId;
    }
}
