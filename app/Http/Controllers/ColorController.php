<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Color;


class ColorController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Color[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $color = $this->get_data(Color::class);
            return $this->response(Response::HTTP_OK, true, 'Colors', ['Colors' => $color]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function store(Request $request)
    {
        $check = $this->check_permission('Color[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'color_name' => 'required|string',
            'color_hexcode' => 'required|string'
        ]);

        try {
            // check if exist
            $color = $this->check_if_exist(Color::class, $request->all());

            if ($color) {
                return $this->response(400, false, 'Color Already Exist');
            }

            // save 
            $color = new Color();
            $color->business_id = $this->auth_user()->business_id;
            $color->user_id = $this->auth_user()->user_id;
            $color = $this->map_data($color, $request->all());
            $color->save();

            return $this->response(Response::HTTP_OK, true, 'Color Created', ['Color' => $color]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function show(Request $request)
    {
        $check = $this->check_permission('Color[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $color = $this->get_data(Color::class, ['color_id' => $this->get_req_id($request)]);
            if (count($color) == 0) {
                return $this->response(400, false, 'Color Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Color', ['Color' => $color]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function update(Request $request)
    {
        $check = $this->check_permission('Color[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        // validate data
        $request->validate([
            'color_name' => 'required|string',
            'color_hexcode' => 'required|string',
            'isAvailable' => 'required|boolean'
        ]);

        try {
            // get brand
            $color = $this->get_data(Color::class, ['color_id' => $this->get_req_id($request)]);
            if (count($color) == 0) {
                return $this->response(400, false, 'Color Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(Color::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'Color Already Exist');
            }

            // save
            $color = $color->first();
            $color = $this->map_data($color, $request->all());
            $color->save();

            return $this->response(Response::HTTP_OK, true, 'Color Updated', ['Color' => $color]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function destroy(Request $request)
    {
        $check = $this->check_permission('Color[delete]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            // get category
            $color = $this->get_data(Color::class, ['color_id' => $this->get_req_id($request)]);
            if (count($color) == 0) {
                return $this->response(400, false, 'Color Not Found');
            }

            // update 
            $color = $color->first();
            $color->isAvailable = false;
            $color->isDeleted = true;
            $color->save();

            return $this->response(Response::HTTP_OK, true, 'Color Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function get_req_id(Request $request)
    {
        $colorId = $request->color_id;
        if (!$colorId) return $this->response(400, false, 'Color ID Not Found');
        return $colorId;
    }
}
