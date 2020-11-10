<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Category[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $category = $this->get_data(Category::class);
            return $this->response(Response::HTTP_OK, true, 'Categories', ['Categories' => $category]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function store(Request $request)
    {
        $check = $this->check_permission('Category[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'category_name' => 'required|string'
        ]);

        try {
            // check if exist
            $category = $this->check_if_exist(Category::class, $request->all());

            if ($category) {
                return $this->response(400, false, 'Category Already Exist');
            }

            // save 
            $category = new Category();
            $category->business_id = $this->auth_user()->business_id;
            $category->user_id = $this->auth_user()->user_id;
            $category = $this->map_data($category, $request->all());
            $category->save();

            return $this->response(Response::HTTP_OK, true, 'Category Created', ['Category' => $category]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function show(Request $request)
    {
        $check = $this->check_permission('Category[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $category = $this->get_data(Category::class, ['category_id' => $this->get_req_id($request)]);
            if (count($category) == 0) {
                return $this->response(400, false, 'Category Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Category', ['Category' => $category]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function update(Request $request)
    {
        $check = $this->check_permission('Category[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        // validate data
        $request->validate([
            'category_name' => 'required|string',
            'isAvailable' => 'required|boolean'
        ]);

        try {
            // get category
            $category = $this->get_data(Category::class, ['category_id' => $this->get_req_id($request)]);
            if (count($category) == 0) {
                return $this->response(400, false, 'Category Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(Category::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'Category Already Exist');
            }

            // save
            $category = $category->first();
            $category = $this->map_data($category, $request->all());
            $category->save();

            return $this->response(Response::HTTP_OK, true, 'Category Updated', ['Category' => $category]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function destroy(Request $request)
    {
        $check = $this->check_permission('Category[delete]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            // get category
            $category = $this->get_data(Category::class, ['category_id' => $this->get_req_id($request)]);
            if (count($category) == 0) {
                return $this->response(400, false, 'Category Not Found');
            }

            // update 
            $category = $category->first();
            $category->isAvailable = false;
            $category->isDeleted = true;
            $category->save();

            return $this->response(Response::HTTP_OK, true, 'Category Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function get_req_id(Request $request)
    {
        $categoryId = $request->category_id;
        if (!$categoryId) return $this->response(400, false, 'Category ID Not Found');
        return $categoryId;
    }
}
