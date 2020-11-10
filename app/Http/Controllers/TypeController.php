<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Type;

class TypeController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Type[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $type = $this->get_data(Type::class);
            return $this->response(Response::HTTP_OK, true, 'Types', ['Types' => $type]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function store(Request $request)
    {
        $check = $this->check_permission('Type[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'type_name' => 'required|string'
        ]);

        try {
            // check if exist
            $type = $this->check_if_exist(Type::class, $request->all());

            if ($type) {
                return $this->response(400, false, 'Type Already Exist');
            }

            // save 
            $type = new Type();
            $type->business_id = $this->auth_user()->business_id;
            $type->user_id = $this->auth_user()->user_id;
            $type = $this->map_data($type, $request->all());
            $type->save();

            return $this->response(Response::HTTP_OK, true, 'Type Created', ['Type' => $type]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function show(Request $request)
    {
        $check = $this->check_permission('Type[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $type = $this->get_data(Type::class, ['type_id' => $this->get_req_id($request)]);
            if (count($type) == 0) {
                return $this->response(400, false, 'Type Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Type', ['Type' => $type]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function update(Request $request)
    {
        $check = $this->check_permission('Type[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        // validate data
        $request->validate([
            'type_name' => 'required|string',
            'isAvailable' => 'required|boolean'
        ]);

        try {
            // get Type
            $type = $this->get_data(Type::class, ['type_id' => $this->get_req_id($request)]);
            if (count($type) == 0) {
                return $this->response(400, false, 'Type Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(Type::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'Type Already Exist');
            }

            // save
            $type = $type->first();
            $type = $this->map_data($type, $request->all());
            $type->save();

            return $this->response(Response::HTTP_OK, true, 'Type Updated', ['Type' => $type]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function destroy(Request $request)
    {
        $check = $this->check_permission('Type[delete]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            // get category
            $type = $this->get_data(Type::class, ['type_id' => $this->get_req_id($request)]);
            if (count($type) == 0) {
                return $this->response(400, false, 'Type Not Found');
            }

            // update 
            $type = $type->first();
            $type->isAvailable = false;
            $type->isDeleted = true;
            $type->save();

            return $this->response(Response::HTTP_OK, true, 'Type Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function get_req_id(Request $request)
    {
        $typeId = $request->type_id;
        if (!$typeId) return $this->response(400, false, 'Type ID Not Found');
        return $typeId;
    }
}
