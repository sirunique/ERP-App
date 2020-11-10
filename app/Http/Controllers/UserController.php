<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

use App\Http\Requests\RegisterRequest;

use App\Models\User;

class UserController extends Controller
{

    public function index()
    {
        $check = $this->check_permission('User[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $user = $this->get_data(User::class);
            return $this->response(Response::HTTP_OK, true, 'Users', ['Users' => $user]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function store(Request $request)
    {
        $check = $this->check_permission('User[add]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        $request->validate([
            'user_fullname' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'user_phone' => 'required|string',
            'user_address' => 'required|string',
            'user_city' => 'required|string',
            'user_country' => 'required|string',
            'password' => 'string',
        ]);

        try {
            // check if exist
            $user = $this->check_if_exist(User::class, $request->all());

            if ($user) {
                return $this->response(400, false, 'User Already Exist');
            }

            // save 
            $user = new User();
            $user->business_id = $this->auth_user()->business_id;
            $user = $this->map_data($user, $request->all());
            $user['password'] = Hash::make($user['password']);
            $user->save();

            return $this->response(Response::HTTP_OK, true, 'User Created', ['User' => $user]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function show(Request $request)
    {
        $check = $this->check_permission('User[view]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            $user = $this->get_data(User::class, ['user_id' => $this->get_req_id($request)]);
            if (count($user) == 0) {
                return $this->response(400, false, 'User Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'User', ['User' => $user]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function update(Request $request)
    {
        $check = $this->check_permission('User[edit]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        // validate data
        $request->validate([
            'user_fullname' => 'required|string',
            'email' => 'required|string|email',
            'user_phone' => 'required|string',
            'user_address' => 'required|string',
            'user_city' => 'required|string',
            'user_country' => 'required|string',
            'isActive' => 'required|boolean',
        ]);

        try {
            // get User
            $user = $this->get_data(User::class, ['user_id' => $this->get_req_id($request)]);
            if (count($user) == 0) {
                return $this->response(400, false, 'User Not Found');
            }

            // check if data already exis
            $exist = $this->check_if_exist(User::class, $request->all());
            if ($exist) {
                return $this->response(400, false, 'User Already Exist');
            }

            // save
            $user = $user->first();
            $user = $this->map_data($user, $request->all());
            $user->save();

            return $this->response(Response::HTTP_OK, true, 'User Updated', ['User' => $user]);
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function destroy(Request $request)
    {
        $check = $this->check_permission('User[delete]');
        if (!$check) return  $this->response(Response::HTTP_FORBIDDEN, false, 'Forbidden Permission is required');

        try {
            // get category
            $user = $this->get_data(User::class, ['user_id' => $this->get_req_id($request)]);
            if (count($user) == 0) {
                return $this->response(400, false, 'User Not Found');
            }

            // update 
            $user = $user->first();
            $user->isActive = false;
            $user->isDeleted = true;
            $user->save();

            return $this->response(Response::HTTP_OK, true, 'User Deleted');
        } catch (\Exception $e) {
            return $this->server_error_response($e);
        }
    }

    public function get_req_id(Request $request)
    {
        $userId = $request->user_id;
        if (!$userId) return $this->response(400, false, 'User ID Not Found');
        return $userId;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            $token = JWTAuth::attempt($credentials);

            if (!$token) {
                return $this->response(Response::HTTP_UNAUTHORIZED, false, 'Invalid Credentials',);
            }
            return $this->response(Response::HTTP_OK, true, 'Login Success', ['token' => $token]);
        } catch (JWTException $e) {
            return $this->server_error_response($e);
        }
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::parseToken()->invalidate();

            return $this->response(Response::HTTP_OK, true, 'Logout Success');
        } catch (JWTException $e) {
            return $this->server_error_response($e);
        }
    }

    public function user()
    {
        try {
            $user = $this->auth_user();
            if (!$user) {
                return $this->response(404, false, 'User Not Found');
            }
            return $this->response(Response::HTTP_OK, true, 'Login Success', ['User' => $user]);
        } catch (JWTException $e) {
            return $this->server_error_response($e);
        }
    }

    // public function register(RegisterRequest $request)
    // {
    // dd($request->all());
    // $validator = Validator::make($request->all(), [
    //     'name' => 'required|string|max:255',
    //     'email' => 'required|string|email|max:255|unique:users',
    //     'password' => 'required|string|min:6|confirmed'
    // ]);

    // if ($validator->fails()) {
    //     return response()->json($validator->errors()->toJson(), 400);
    // }
    // $user = new User();
    // dd($user);

    // $user = User::create([
    //     'name' => $request->get('name'),
    //     'email' => $request->get('email'),
    //     'password' => Hash::make($request->get('password')),
    // ]);

    // $token = JWTAuth::fromUser($user);

    // return response()->json(
    //     [
    //         'success' => true,
    //         'user' => $user,
    //         // 'token' => $token
    //     ],
    // 201
    //         Response::HTTP_OK
    //     );
    // }
}
