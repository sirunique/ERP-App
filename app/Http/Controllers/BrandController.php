<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Brand[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $brand = $this->get_data(Brand::class);
            return $this->response(Response::HTTP_OK, true, 'Brands', ['Brands' => $brand]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function store(Request $request)
    {
        $check = $this->check_permission('Brand[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'brand_name' => 'required|string'
        ]);

        try {
            // check if exist
            $brand = $this->check_if_exist(Brand::class, $request->all());

            if ($brand) {
                return $this->response(400, false, 'Brand Already Exist');
            }

            // save 
            $brand = new Brand();
            $brand->business_id = $this->auth_user()->business_id;
            $brand->user_id = $this->auth_user()->user_id;
            $brand =  $this->map_data($brand, $request->all());
            $brand->save();

            return $this->response(Response::HTTP_OK, true, 'Brand Created', ['Brand' => $brand]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function show(Request $request)
    {
        $check = $this->check_permission('Brand[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $brand = $this->get_data(Brand::class, ['brand_id' => $this->get_req_id($request)]);
            if (count($brand) == 0) {
                return $this->response(400, false, 'Brand Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Brand', ['Brand' => $brand]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function update(Request $request)
    {
        $check = $this->check_permission('Brand[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        // validate data
        $request->validate([
            'brand_name' => 'required|string',
            'isAvailable' => 'required|boolean'
        ]);

        try {
            // get brand
            $brand = $this->get_data(Brand::class, ['brand_id' => $this->get_req_id($request)]);
            if (count($brand) == 0) {
                return $this->response(400, false, 'Brand Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(Brand::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'Brand Already Exist');
            }

            // save
            $brand = $brand->first();
            $brand = $this->map_data($brand, $request->all());
            $brand->save();

            return $this->response(Response::HTTP_OK, true, 'Brand Updated', ['Brand' => $brand]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function destroy(Request $request)
    {
        $check = $this->check_permission('Brand[delete]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            // get category
            $brand = $this->get_data(Brand::class, ['brand_id' => $this->get_req_id($request)]);
            if (count($brand) == 0) {
                return $this->response(400, false, 'Brand Not Found');
            }

            // update 
            $brand = $brand->first();
            $brand->isAvailable = false;
            $brand->isDeleted = true;
            $brand->save();

            return $this->response(Response::HTTP_OK, true, 'Brand Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function get_req_id(Request $request)
    {
        $brandId = $request->brand_id;
        if (!$brandId) return $this->response(400, false, 'Brand ID Not Found');
        return $brandId;
    }
}
