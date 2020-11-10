<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Supplier[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $supplier = $this->get_data(Supplier::class);
            return $this->response(Response::HTTP_OK, true, 'Suppliers', ['Suppliers' => $supplier]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function store(Request $request)
    {
        $check = $this->check_permission('Supplier[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'supplier_company_name' => 'required|string',
            'supplier_vat_no' => 'required|string',
            'supplier_email' => 'required|string|email|unique:supplier',
            'supplier_phone_no' => 'required|string',
            'supplier_address' => 'required|string',
            'supplier_country' => 'required|string',
            'supplier_city' => 'required|string',
            'supplier_state' => 'required|string',
            'supplier_postal_code' => 'required|string',
        ]);

        try {
            // check if exist
            $supplier = $this->check_if_exist(Supplier::class, $request->all());

            if ($supplier) {
                return $this->response(400, false, 'Supplier Already Exist');
            }

            // save 
            $supplier = new Supplier();
            $supplier->business_id = $this->auth_user()->business_id;
            $supplier->user_id = $this->auth_user()->user_id;
            $supplier = $this->map_data($supplier, $request->all());
            $supplier->save();

            return $this->response(Response::HTTP_OK, true, 'Supplier Created', ['Supplier' => $supplier]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function show(Request $request)
    {
        $check = $this->check_permission('Supplier[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $supplier = $this->get_data(Supplier::class, ['supplier_id' => $this->get_req_id($request)]);
            if (count($supplier) == 0) {
                return $this->response(400, false, 'Supplier Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Supplier', ['Supplier' => $supplier]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function update(Request $request)
    {
        $check = $this->check_permission('Supplier[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        // validate data
        $request->validate([
            'supplier_company_name' => 'required|string',
            'supplier_vat_no' => 'required|string',
            'supplier_email' => 'required|string|email',
            'supplier_phone_no' => 'required|string',
            'supplier_address' => 'required|string',
            'supplier_country' => 'required|string',
            'supplier_city' => 'required|string',
            'supplier_state' => 'required|string',
            'supplier_postal_code' => 'required|string',
            'isAvailable' => 'required|boolean'
        ]);

        try {
            // get Size
            $supplier = $this->get_data(Supplier::class, ['supplier_id' => $this->get_req_id($request)]);
            if (count($supplier) == 0) {
                return $this->response(400, false, 'Supplier Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(Supplier::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'Supplier Already Exist');
            }

            // save
            $supplier = $supplier->first();
            $supplier = $this->map_data($supplier, $request->all());
            $supplier->save();

            return $this->response(Response::HTTP_OK, true, 'Supplier Updated', ['Supplier' => $supplier]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function destroy(Request $request)
    {
        $check = $this->check_permission('Supplier[delete]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            // get category
            $supplier = $this->get_data(Supplier::class, ['supplier_id' => $this->get_req_id($request)]);
            if (count($supplier) == 0) {
                return $this->response(400, false, 'Supplier Not Found');
            }

            // update 
            $supplier = $supplier->first();
            $supplier->isAvailable = false;
            $supplier->isDeleted = true;
            $supplier->save();

            return $this->response(Response::HTTP_OK, true, 'Supplier Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function get_req_id(Request $request)
    {
        $supplierId = $request->supplier_id;
        if (!$supplierId) return $this->response(400, false, 'Supplier ID Not Found');
        return $supplierId;
    }
}
