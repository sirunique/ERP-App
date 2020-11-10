<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Size;

class SizeController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Size[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $size = $this->get_data(Size::class);
            return $this->response(Response::HTTP_OK, true, 'Sizes', ['Sizes' => $size]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function store(Request $request)
    {
        $check = $this->check_permission('Size[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'size_name' => 'required|string',
            'size_short_name' => 'required|string',
        ]);

        try {
            // check if exist
            $size = $this->check_if_exist(Size::class, $request->all());

            if ($size) {
                return $this->response(400, false, 'Size Already Exist');
            }

            // save 
            $size = new Size();
            $size->business_id = $this->auth_user()->business_id;
            $size->user_id = $this->auth_user()->user_id;
            $size = $this->map_data($size, $request->all());
            $size->save();

            return $this->response(Response::HTTP_OK, true, 'Size Created', ['Size' => $size]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function show(Request $request)
    {
        $check = $this->check_permission('Size[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $size = $this->get_data(Size::class, ['size_id' => $this->get_req_id($request)]);
            if (count($size) == 0) {
                return $this->response(400, false, 'Size Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Size', ['Size' => $size]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function update(Request $request)
    {
        $check = $this->check_permission('Size[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        // validate data
        $request->validate([
            'size_name' => 'required|string',
            'size_short_name' => 'required|string',
            'isAvailable' => 'required|boolean'
        ]);

        try {
            // get Size
            $size = $this->get_data(Size::class, ['size_id' => $this->get_req_id($request)]);
            if (count($size) == 0) {
                return $this->response(400, false, 'Size Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(Size::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'Size Already Exist');
            }

            // save
            $size = $size->first();
            $size = $this->map_data($size, $request->all());
            $size->save();

            return $this->response(Response::HTTP_OK, true, 'Size Updated', ['Size' => $size]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function destroy(Request $request)
    {
        $check = $this->check_permission('Size[delete]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            // get category
            $size = $this->get_data(Size::class, ['size_id' => $this->get_req_id($request)]);
            if (count($size) == 0) {
                return $this->response(400, false, 'Size Not Found');
            }

            // update 
            $size = $size->first();
            $size->isAvailable = false;
            $size->isDeleted = true;
            $size->save();

            return $this->response(Response::HTTP_OK, true, 'Size Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function get_req_id(Request $request)
    {
        $sizeId = $request->size_id;
        if (!$sizeId) return $this->response(400, false, 'Size ID Not Found');
        return $sizeId;
    }
}
