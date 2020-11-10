<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Role[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $role = $this->get_data(Role::class);
            return $this->response(Response::HTTP_OK, true, 'Roles', ['Roles' => $role]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function store(Request $request)
    {
        $check = $this->check_permission('Role[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'role_title' => 'required|string'
        ]);

        try {
            // check if exist
            $role = $this->check_if_exist(Role::class, $request->all());

            if ($role) {
                return $this->response(400, false, 'Role Already Exist');
            }

            // save 
            $role = new Role();
            $role->business_id = $this->auth_user()->business_id;
            // $role->user_id = $this->auth_user()->user_id;
            $role = $this->map_data($role, $request->all());
            $role->save();

            return $this->response(Response::HTTP_OK, true, 'Role Created', ['Role' => $role]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function show(Request $request)
    {
        $check = $this->check_permission('Role[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $role = $this->get_data(Role::class, ['role_id' => $this->get_req_id($request)]);
            if (count($role) == 0) {
                return $this->response(400, false, 'Role Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Role', ['Role' => $role]);
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
            'role_title' => 'required|string',
            'isAvailable' => 'required|boolean'
        ]);

        try {
            // get Role
            $role = $this->get_data(Role::class, ['role_id' => $this->get_req_id($request)]);
            if (count($role) == 0) {
                return $this->response(400, false, 'Role Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(Role::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'Role Already Exist');
            }

            // save
            $role = $role->first();
            $role = $this->map_data($role, $request->all());
            $role->save();

            return $this->response(Response::HTTP_OK, true, 'Role Updated', ['Role' => $role]);
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
            $role = $this->get_data(Role::class, ['role_id' => $this->get_req_id($request)]);
            if (count($role) == 0) {
                return $this->response(400, false, 'Role Not Found');
            }

            // update 
            $role = $role->first();
            $role->isAvailable = false;
            $role->isDeleted = true;
            $role->save();

            return $this->response(Response::HTTP_OK, true, 'Role Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function get_req_id(Request $request)
    {
        $roleId = $request->role_id;
        if (!$roleId) return $this->response(400, false, 'Role ID Not Found');
        return $roleId;
    }
}
