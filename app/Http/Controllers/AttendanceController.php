<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Attendance;
use App\Models\Employee;
// use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Attendance[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $attendance = $this->get_data(Attendance::class);
            return $this->response(Response::HTTP_OK, true, 'Attendances', ['Attendances' => $attendance]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function store(Request $request)
    {
        $check = $this->check_permission('Attendance[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            // 'employee_id' => 'required|integer|exists:employee,employee_id',
            'employee_id' => [
                'required', 'integer',
                Rule::exists('employee')->where(function ($query) {
                    $query->where(['isActive' =>  1, 'business_id' => $this->auth_user()->business_id]);
                })
            ],
            'attendance_check_in' => 'required|string',
            'attendance_check_out' => 'required|string',
            'attendance_date' => 'required|string',
            'attendance_note' => 'required|string',
        ]);

        try {
            // check if exist
            $attendance = $this->check_if_exist(Attendance::class, $request->all());

            if ($attendance) {
                return $this->response(400, false, 'Attendance Already Exist');
            }

            // save 
            $attendance = new Attendance();
            $attendance->business_id = $this->auth_user()->business_id;
            $attendance->user_id = $this->auth_user()->user_id;
            $attendance->attendance_status = 'Yet to calculate';
            $attendance = $this->map_data($attendance, $request->all());
            $attendance->save();

            return $this->response(Response::HTTP_OK, true, 'Attendance Created', ['Attendance' => $attendance]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function show(Request $request)
    {
        $check = $this->check_permission('Attendance[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $attendance = $this->get_data(Attendance::class, ['attendance_id' => $this->get_req_id($request)]);
            if (count($attendance) == 0) {
                return $this->response(400, false, 'Attendance Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Attendance', ['Attendance' => $attendance]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function update(Request $request)
    {
        $check = $this->check_permission('Attendance[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        // validate data
        $request->validate([
            'employee_id' => [
                'required', 'integer',
                Rule::exists('employee')->where(function ($query) {
                    $query->where(['isActive' => 1]);
                })
            ],
            'attendance_check_in' => 'required|string',
            'attendance_check_out' => 'required|string',
            'attendance_date' => 'required|string',
            'attendance_note' => 'required|string',
        ]);

        try {
            // get Attendance
            $attendance = $this->get_data(Attendance::class, ['attendance_id' => $this->get_req_id($request)]);
            if (count($attendance) == 0) {
                return $this->response(400, false, 'Attendance Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(Attendance::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'Attendance Already Exist');
            }

            // save
            $attendance = $attendance->first();
            // $attendance->attendance_status = 'Yet to calculate';
            $attendance = $this->map_data($attendance, $request->all());
            $attendance->save();

            return $this->response(Response::HTTP_OK, true, 'Attendance Updated', ['Attendance' => $attendance]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function destroy(Request $request)
    {
        $check = $this->check_permission('Attendance[delete]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            // get category
            $attendance = $this->get_data(Attendance::class, ['attendance_id' => $this->get_req_id($request)]);
            if (count($attendance) == 0) {
                return $this->response(400, false, 'Attendance Not Found');
            }

            // update 
            $attendance = $attendance->first();
            $attendance->isAvailable = false;
            $attendance->isDeleted = true;
            $attendance->save();

            return $this->response(Response::HTTP_OK, true, 'Attendance Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function get_req_id(Request $request)
    {
        $attendanceId = $request->attendance_id;
        if (!$attendanceId) return $this->response(400, false, 'Attendance ID Not Found');
        return $attendanceId;
    }
}
