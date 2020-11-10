<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\MailSetting;

class MailSettingController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Settings[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $mailSetting = $this->get_data(MailSetting::class);
            return $this->response(Response::HTTP_OK, true, 'Settings', ['Settings' => $mailSetting]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function store(Request $request)
    {
        $check = $this->check_permission('Settings[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'mail_host' => 'required|string',
            'mail_port' => 'required|string',
            'mail_address' => 'required|string',
            'mail_password' => 'required|string',
            'mail_from_name' => 'required|string',
            'mail_encryption' => 'required|string'
        ]);

        try {
            // check if exist
            $mailSetting = $this->check_if_exist(MailSetting::class, $request->all());

            if ($mailSetting) {
                return $this->response(400, false, 'Mail Setting Already Exist');
            }

            // save
            $mailSetting = new MailSetting();
            $mailSetting->business_id = $this->auth_user()->business_id;
            $mailSetting->user_id = $this->auth_user()->user_id;
            $mailSetting = $this->map_data($mailSetting, $request->all());
            $mailSetting->save();

            return $this->response(Response::HTTP_OK, true, 'Mail Setting Created', ['Mail Setting' => $mailSetting]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function show(Request $request)
    {
        $check = $this->check_permission('Settings[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $mailSetting = $this->get_data(MailSetting::class, ['mail_setting_id' => $this->get_req_id($request)]);
            if (count($mailSetting) == 0) {
                return $this->response(400, false, 'Settings Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Settings', ['Settings' => $mailSetting]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function update(Request $request)
    {
        $check = $this->check_permission('Settings[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'mail_host' => 'required|string',
            'mail_port' => 'required|string',
            'mail_address' => 'required|string',
            'mail_password' => 'required|string',
            'mail_from_name' => 'required|string',
            'mail_encryption' => 'required|string',
            'isDefault' => 'required|boolean'
        ]);

        try {
            // get setting
            $mailSetting = $this->get_data(MailSetting::class, ['mail_setting_id' => $this->get_req_id($request)]);
            if (count($mailSetting) == 0) {
                return $this->response(400, false, 'Mail Settings Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(MailSetting::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'Mail Settings Already Exist');
            }

            // check if there is active default settings
            $getDefault = $this->get_data(MailSetting::class, ['isDefault' => 1]);
            if ($request->isDefault && (count($getDefault) == 1) && ($this->get_req_id($request) != $getDefault[0]['mail_setting_id'])) {
                return $this->response(400, false, 'Mail Settings Already Have a Default');
            }

            // save 
            $mailSetting = $mailSetting->first();
            $mailSetting = $this->map_data($mailSetting, $request->all());
            $mailSetting->save();

            return $this->response(Response::HTTP_OK, true, 'Mail Settings Updated', ['Mail Settings' => $mailSetting]);
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
            $mailSetting = $this->get_data(MailSetting::class, ['mail_setting_id' => $this->get_req_id($request)]);
            if (count($mailSetting) == 0) {
                return $this->response(400, false, 'Mail Settings Not Found');
            }

            // update 
            $mailSetting = $mailSetting->first();
            $mailSetting->isDefault = false;
            $mailSetting->isDeleted = true;
            $mailSetting->save();

            return $this->response(Response::HTTP_OK, true, 'Mail Settings Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function get_req_id(Request $request)
    {
        $mailSettingId = $request->mail_setting_id;
        if (!$mailSettingId) return $this->response(400, false, 'Mail Setting ID Not Found');
        return $mailSettingId;
    }
}
