<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Department[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $department = $this->get_data(Department::class);
            return $this->response(Response::HTTP_OK, true, 'Departments', ['Departments' => $department]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function store(Request $request)
    {
        $check = $this->check_permission('Department[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'department_name' => 'required|string',
            'department_description' => 'required|string',
        ]);

        try {
            // check if exist
            $department = $this->check_if_exist(Department::class, $request->all());

            if ($department) {
                return $this->response(400, false, 'Department Already Exist');
            }

            // save 
            $department = new Department();
            $department->business_id = $this->auth_user()->business_id;
            $department->user_id = $this->auth_user()->user_id;
            $department = $this->map_data($department, $request->all());
            $department->save();

            return $this->response(Response::HTTP_OK, true, 'Department Created', ['Department' => $department]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function show(Request $request)
    {
        $check = $this->check_permission('Department[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $department = $this->get_data(Department::class, ['department_id' => $this->get_req_id($request)]);
            if (count($department) == 0) {
                return $this->response(400, false, 'Department Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Department', ['Department' => $department]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function update(Request $request)
    {
        $check = $this->check_permission('Department[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        // validate data
        $request->validate([
            'department_name' => 'required|string',
            'department_description' => 'required|string',
            'isAvailable' => 'required|boolean'
        ]);

        try {
            // get Customer
            $department = $this->get_data(Department::class, ['department_id' => $this->get_req_id($request)]);
            if (count($department) == 0) {
                return $this->response(400, false, 'Department Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(Department::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'Department Already Exist');
            }

            // save
            $department = $department->first();
            $department = $this->map_data($department, $request->all());
            $department->save();

            return $this->response(Response::HTTP_OK, true, 'Department Updated', ['Department' => $department]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function destroy(Request $request)
    {
        $check = $this->check_permission('Department[delete]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            // get category
            $department = $this->get_data(Department::class, ['department_id' => $this->get_req_id($request)]);
            if (count($department) == 0) {
                return $this->response(400, false, 'Department Not Found');
            }

            // update 
            $department = $department->first();
            $department->isAvailable = false;
            $department->isDeleted = true;
            $department->save();

            return $this->response(Response::HTTP_OK, true, 'Department Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function get_req_id(Request $request)
    {
        $departmentId = $request->department_id;
        if (!$departmentId) return $this->response(400, false, 'Department ID Not Found');
        return $departmentId;
    }
}
