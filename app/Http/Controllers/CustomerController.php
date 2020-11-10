<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $check = $this->check_permission('Customer[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $customer = $this->get_data(Customer::class);
            return $this->response(Response::HTTP_OK, true, 'Customers', ['Customers' => $customer]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function store(Request $request)
    {
        $check = $this->check_permission('Customer[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|string|email|unique:customer',
            'customer_phone_no' => 'required|string',
            'customer_address' => 'required|string',
            'customer_country' => 'required|string',
            'customer_city' => 'required|string',
            'customer_state' => 'required|string',
            'customer_postal_code' => 'required|string',
        ]);

        try {
            // check if exist
            $customer = $this->check_if_exist(Customer::class, $request->all());

            if ($customer) {
                return $this->response(400, false, 'Customer Already Exist');
            }

            // save 
            $customer = new Customer();
            $customer->business_id = $this->auth_user()->business_id;
            $customer->user_id = $this->auth_user()->user_id;
            $customer = $this->map_data($customer, $request->all());
            $customer->save();

            return $this->response(Response::HTTP_OK, true, 'Customer Created', ['Customer' => $customer]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function show(Request $request)
    {
        $check = $this->check_permission('Customer[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $customer = $this->get_data(Customer::class, ['customer_id' => $this->get_req_id($request)]);
            if (count($customer) == 0) {
                return $this->response(400, false, 'Customer Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Customer', ['Customer' => $customer]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function update(Request $request)
    {
        $check = $this->check_permission('Customer[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        // validate data
        $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|string|email',
            'customer_phone_no' => 'required|string',
            'customer_address' => 'required|string',
            'customer_country' => 'required|string',
            'customer_city' => 'required|string',
            'customer_state' => 'required|string',
            'customer_postal_code' => 'required|string',
            'isAvailable' => 'required|boolean'
        ]);

        try {
            // get Customer
            $customer = $this->get_data(Customer::class, ['customer_id' => $this->get_req_id($request)]);
            if (count($customer) == 0) {
                return $this->response(400, false, 'Customer Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(Customer::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'Customer Already Exist');
            }

            // save
            $customer = $customer->first();
            $customer = $this->map_data($customer, $request->all());
            $customer->save();

            return $this->response(Response::HTTP_OK, true, 'Customer Updated', ['Customer' => $customer]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function destroy(Request $request)
    {
        $check = $this->check_permission('Customer[delete]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            // get category
            $customer = $this->get_data(Customer::class, ['customer_id' => $this->get_req_id($request)]);
            if (count($customer) == 0) {
                return $this->response(400, false, 'Customer Not Found');
            }

            // update 
            $customer = $customer->first();
            $customer->isAvailable = false;
            $customer->isDeleted = true;
            $customer->save();

            return $this->response(Response::HTTP_OK, true, 'Customer Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
    public function get_req_id(Request $request)
    {
        $customerId = $request->customer_id;
        if (!$customerId) return $this->response(400, false, 'Customer ID Not Found');
        return $customerId;
    }
}
