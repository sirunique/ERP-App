<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SmsSetting;

class SmsSettingController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Settings[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $smsSetting = $this->get_data(SmsSetting::class);
            return $this->response(Response::HTTP_OK, true, 'Settings', ['Settings' => $smsSetting]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function store(Request $request)
    {
        $check = $this->check_permission('Settings[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'sms_twilio_account_sid' => 'required|string',
            'sms_twilio_auth_token' => 'required|string',
            'sms_twilio_number' => 'required|string',
        ]);

        try {
            // check if exist
            $smsSetting = $this->check_if_exist(SmsSetting::class, $request->all());

            if ($smsSetting) {
                return $this->response(400, false, 'SMS Setting Already Exist');
            }

            // save
            $smsSetting = new SmsSetting();
            $smsSetting->business_id = $this->auth_user()->business_id;
            $smsSetting->user_id = $this->auth_user()->user_id;
            $smsSetting = $this->map_data($smsSetting, $request->all());
            $smsSetting->save();

            return $this->response(Response::HTTP_OK, true, 'SMS Setting Created', ['SMS Setting' => $smsSetting]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function show(Request $request)
    {
        $check = $this->check_permission('Settings[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $smsSetting = $this->get_data(SmsSetting::class, ['sms_setting_id' => $this->get_req_id($request)]);
            if (count($smsSetting) == 0) {
                return $this->response(400, false, 'Settings Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Settings', ['Settings' => $smsSetting]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function update(Request $request)
    {
        $check = $this->check_permission('Settings[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'sms_twilio_account_sid' => 'required|string',
            'sms_twilio_auth_token' => 'required|string',
            'sms_twilio_number' => 'required|string',
            'isDefault' => 'required|boolean'
        ]);

        try {
            // get setting
            $smsSetting = $this->get_data(SmsSetting::class, ['sms_setting_id' => $this->get_req_id($request)]);
            if (count($smsSetting) == 0) {
                return $this->response(400, false, 'SMS Settings Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(SmsSetting::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'SMS Settings Already Exist');
            }

            // check if there is active default settings
            $getDefault = $this->get_data(SmsSetting::class, ['isDefault' => 1]);
            if ($request->isDefault && (count($getDefault) == 1) && ($this->get_req_id($request) != $getDefault[0]['sms_setting_id'])) {
                return $this->response(400, false, 'SMS Settings Already Have a Default');
            }

            // save 
            $smsSetting = $smsSetting->first();
            $smsSetting = $this->map_data($smsSetting, $request->all());
            $smsSetting->save();

            return $this->response(Response::HTTP_OK, true, 'SMS Settings Updated', ['SMS Settings' => $smsSetting]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function destroy(Request $request)
    {
        $check = $this->check_permission('Settings[delete]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            // get category
            $smsSetting = $this->get_data(SmsSetting::class, ['sms_setting_id' => $this->get_req_id($request)]);
            if (count($smsSetting) == 0) {
                return $this->response(400, false, 'SMS Settings Not Found');
            }

            // update 
            $smsSetting = $smsSetting->first();
            $smsSetting->isDefault = false;
            $smsSetting->isDeleted = true;
            $smsSetting->save();

            return $this->response(Response::HTTP_OK, true, 'SMS Settings Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function get_req_id(Request $request)
    {
        $smsSettingId = $request->sms_setting_id;
        if (!$smsSettingId) return $this->response(400, false, 'SMS Setting ID Not Found');
        return $smsSettingId;
    }
}
