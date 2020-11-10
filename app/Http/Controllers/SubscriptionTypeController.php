<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SubscriptionType;

class SubscriptionTypeController extends Controller
{
    public function index()
    {
        try {
            $sub_type = $this->get_data(SubscriptionType::class);
            return $this->response(Response::HTTP_OK, true, 'SubTypes', ['SubTypes' => $sub_type]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }
}
