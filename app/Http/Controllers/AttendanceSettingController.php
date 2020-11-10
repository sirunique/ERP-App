<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AttendanceSetting;

class AttendanceSettingController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Attendance[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $attendancesetting = $this->get_data(AttendanceSetting::class);
            return $this->response(Response::HTTP_OK, true, 'Settings', ['Settings' => $attendancesetting]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function store(Request $request)
    {
        $check = $this->check_permission('Attendance[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'attendance_settings_check_in' => 'required|string',
            'attendance_settings_check_out' => 'required|string',
        ]);

        try {
            // check if exist
            $attendancesetting = $this->check_if_exist(AttendanceSetting::class, $request->all());

            if ($attendancesetting) {
                return $this->response(400, false, 'Attendance Setting Already Exist');
            }

            // save
            $attendancesetting = new AttendanceSetting();
            $attendancesetting->business_id = $this->auth_user()->business_id;
            $attendancesetting->user_id = $this->auth_user()->user_id;
            $attendancesetting = $this->map_data($attendancesetting, $request->all());
            $attendancesetting->save();

            return $this->response(Response::HTTP_OK, true, 'Attendance Setting Created', ['Attendance Setting' => $attendancesetting]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function show(Request $request)
    {
        $check = $this->check_permission('Attendance[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $attendancesetting = $this->get_data(AttendanceSetting::class, ['attendance_settings_id' => $this->get_req_id($request)]);
            if (count($attendancesetting) == 0) {
                return $this->response(400, false, 'Attendance Settings Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Attendance Settings', ['Attendance Settings' => $attendancesetting]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function update(Request $request)
    {
        $check = $this->check_permission('Attendance[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'attendance_settings_check_in' => 'required|string',
            'attendance_settings_check_out' => 'required|string',
            'isDefault' => 'required|boolean'
        ]);

        try {
            // get setting
            $attendancesetting = $this->get_data(AttendanceSetting::class, ['attendance_settings_id' => $this->get_req_id($request)]);
            if (count($attendancesetting) == 0) {
                return $this->response(400, false, 'Attendance Settings Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(AttendanceSetting::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'Attendance Settings Already Exist');
            }

            // check if there is active default settings
            $check = $this->check_if_exist(AttendanceSetting::class, ['isDefault' => 1]);
            if ($check) {
                return $this->response(400, false, 'Attendance Settings Already Have a Default');
            }

            // save 
            $attendancesetting = $attendancesetting->first();
            $attendancesetting = $this->map_data($attendancesetting, $request->all());
            $attendancesetting->save();

            return $this->response(Response::HTTP_OK, true, 'Attendance Settings Updated', ['Attendance Settings' => $attendancesetting]);
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
            $attendancesetting = $this->get_data(AttendanceSetting::class, ['attendance_settings_id' => $this->get_req_id($request)]);
            if (count($attendancesetting) == 0) {
                return $this->response(400, false, 'Attendance Settings Not Found');
            }

            // update 
            $attendancesetting = $attendancesetting->first();
            $attendancesetting->isDefault = false;
            $attendancesetting->isDeleted = true;
            $attendancesetting->save();

            return $this->response(Response::HTTP_OK, true, 'Attendance Settings Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function get_req_id(Request $request)
    {
        $attendancesettingId = $request->attendance_settings_id;
        if (!$attendancesettingId) return $this->response(400, false, 'Attendance Setting ID Not Found');
        return $attendancesettingId;
    }
}
