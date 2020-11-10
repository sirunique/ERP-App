<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Product[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $product = $this->get_data(Product::class);
            return $this->response(Response::HTTP_OK, true, 'Products', ['Products' => $product]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function store(Request $request)
    {
        $check = $this->check_permission('Product[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'product_name' => 'required|string',
            'product_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'product_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'product_reorder_level' => 'required|numeric',
        ]);

        $checkname = $this->check_if_exist(Product::class, ['product_name' => $this->auth_user()->business_id, 'isAvailable' => 1, 'product_name' => $request->product_name]);
        if ($checkname) {
            return $this->response(400, false, 'Product Name Already Exist');
        }

        try {
            // check if exist
            $product = $this->check_if_exist(Product::class, $request->all());

            if ($product) {
                return $this->response(400, false, 'Product Already Exist');
            }

            // save 
            $product = new Product();
            $product->business_id = $this->auth_user()->business_id;
            $product->user_id = $this->auth_user()->user_id;
            $product = $this->map_data($product, $request->all());
            $product->save();

            return $this->response(Response::HTTP_OK, true, 'Product Created', ['Product' => $product]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function show(Request $request)
    {
        $check = $this->check_permission('Product[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $product = $this->get_data(Product::class, ['product_id' => $this->get_req_id($request)]);
            if (count($product) == 0) {
                return $this->response(400, false, 'Product Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Product', ['Product' => $product]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function update(Request $request)
    {
        $check = $this->check_permission('Product[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        // validate data
        $request->validate([
            'product_name' => 'required|string',
            'product_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'product_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'product_reorder_level' => 'required|numeric',
            'isAvailable' => 'required|boolean'
        ]);

        try {
            // get category
            $product = $this->get_data(Product::class, ['product_id' => $this->get_req_id($request)]);
            if (count($product) == 0) {
                return $this->response(400, false, 'Product Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(Product::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'Product Already Exist');
            }

            // save
            $product = $product->first();
            $product = $this->map_data($product, $request->all());
            $product->save();

            return $this->response(Response::HTTP_OK, true, 'Product Updated', ['Product' => $product]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function destroy(Request $request)
    {
        $check = $this->check_permission('Product[delete]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            // get category
            $product = $this->get_data(Product::class, ['product_id' => $this->get_req_id($request)]);
            if (count($product) == 0) {
                return $this->response(400, false, 'Product Not Found');
            }

            // update 
            $product = $product->first();
            $product->isAvailable = false;
            $product->isDeleted = true;
            $product->save();

            return $this->response(Response::HTTP_OK, true, 'Product Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function get_req_id(Request $request)
    {
        $categoryId = $request->product_id;
        if (!$categoryId) return $this->response(400, false, 'Product ID Not Found');
        return $categoryId;
    }
}
