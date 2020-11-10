<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Employee[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $employee = $this->get_data(Employee::class);
            return $this->response(Response::HTTP_OK, true, 'Employee', ['Employee' => $employee]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function store(Request $request)
    {
        $check = $this->check_permission('Employee[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'department_id' => [
                'required', 'integer',
                Rule::exists('department')->where(function ($query) {
                    $query->where(['isAvailable' => 1, 'business_id' => $this->auth_user()->business_id]);
                })
            ],
            'employee_fullname' => 'required|string',
            'employee_email' => 'required|string|email|unique:employee',
            'employee_phone' => 'required|string',
            'employee_address' => 'required|string',
            'employee_city' => 'required|string',
            'employee_country' => 'required|string',

            'isUser' => 'boolean',
            'password' => 'string',
        ]);

        DB::beginTransaction();
        try {

            $data = [];
            $data = $this->map_data($data, $request->all());

            $extract_data = [];
            if (array_key_exists('isUser', $data) && $data['isUser']) {
                $extract_data['user_fullname'] = $data['employee_fullname'];
                $extract_data['user_phone'] = $data['employee_phone'];
                $extract_data['user_address'] = $data['employee_address'];
                $extract_data['user_city'] = $data['employee_city'];
                $extract_data['user_country'] = $data['employee_country'];
                $extract_data['email'] = $data['employee_email'];
                $extract_data['password'] = Hash::make($data['password']);

                unset($data['isUser']);
                unset($data['password']);

                $user = new User();
                $user->business_id = $this->auth_user()->business_id;
                $user = $this->map_data($user, $extract_data);
                $user->save();
            }

            // check if exist
            $employee = $this->check_if_exist(Employee::class, $data);

            if ($employee) {
                return $this->response(400, false, 'Employee Already Exist');
            }

            // save 
            $employee = new Employee();
            $employee->business_id = $this->auth_user()->business_id;
            $employee->user_id = $this->auth_user()->user_id;
            $employee = $this->map_data($employee, $data);
            $employee->save();

            DB::commit();
            return $this->response(Response::HTTP_OK, true, 'Employee Created', ['Employee' => $employee]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->server_error_response($e);
        }
    }
    public function show(Request $request)
    {
        $check = $this->check_permission('Employee[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $employee = $this->get_data(Employee::class, ['employee_id' => $this->get_req_id($request)]);
            if (count($employee) == 0) {
                return $this->response(400, false, 'Employee Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Employee', ['Employee' => $employee]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function update(Request $request)
    {
        $check = $this->check_permission('Employee[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        // validate data
        $request->validate([
            'department_id' => 'required|integer',
            'employee_fullname' => 'required|string',
            'employee_email' => 'required|string|email',
            'employee_phone' => 'required|string',
            'employee_address' => 'required|string',
            'employee_address' => 'required|string',
            'employee_city' => 'required|string',
            'employee_country' => 'required|string',
            'isActive' => 'required|boolean',
        ]);

        try {
            // get Employee
            $employee = $this->get_data(Employee::class, ['employee_id' => $this->get_req_id($request)]);
            if (count($employee) == 0) {
                return $this->response(400, false, 'Employee Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(Employee::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'Employee Already Exist');
            }

            // save
            $employee = $employee->first();
            $employee = $this->map_data($employee, $request->all());
            $employee->save();

            return $this->response(Response::HTTP_OK, true, 'Employee Updated', ['Employee' => $employee]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function destroy(Request $request)
    {
        $check = $this->check_permission('Employee[delete]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            // get category
            $employee = $this->get_data(Employee::class, ['employee_id' => $this->get_req_id($request)]);
            if (count($employee) == 0) {
                return $this->response(400, false, 'Employee Not Found');
            }

            // update 
            $employee = $employee->first();
            $employee->isDeleted = true;
            $employee->save();

            return $this->response(Response::HTTP_OK, true, 'Employee Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function get_req_id(Request $request)
    {
        $employeeId = $request->employee_id;
        if (!$employeeId) return $this->response(400, false, 'Employee ID Not Found');
        return $employeeId;
    }
}
