<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetupRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\Business;
use App\Models\User;
use App\Models\SubscriptionDetail;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Permission;
use App\Models\PermissionRole;


class SetupController extends Controller
{
    public function setup(SetupRequest $request)
    {
        $validated = $request->validated();

        // dd($request->all());

        DB::beginTransaction();
        $success = false;
        try {
            // check if business exist in sub_details and is active is true
            $business = DB::table('business')->where([
                ['business_email', '=', $validated['business_email']],
                ['business_name', '=', $validated['business_name']]
            ])->exists();
            // Bussines already exist
            if ($business) {
                return response()->json(['success' => false, 'message' => 'Business Already Exist'], 400);
            }

            // Save Business
            $business = new Business();
            $business->business_name = $validated['business_name'];
            $business->business_email = $validated['business_email'];
            $business->business_phone = $validated['business_phone'];
            $business->business_address = $validated['business_address'];
            $business->business_country = $validated['business_country'];
            $business->business_timezone = $validated['business_timezone'];
            $business->business_default_language = $validated['business_default_language'];
            $business->business_currency_symbol = $validated['business_currency_symbol'];
            $business->save();

            // Save user Admin Data
            $user = new User();
            $user->business_id = $business->business_id;
            $user->user_fullname = $validated['user_fullname'];
            $user->user_phone = $validated['user_phone'];
            $user->user_address = $validated['user_address'];
            $user->user_city = $validated['user_city'];
            $user->user_country = $validated['user_country'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->save();

            // Save subscription details
            $sub_detail = new SubscriptionDetail();
            $sub_detail->business_id = $business->business_id;
            $sub_detail->sub_type_id = $validated['sub_type_id'];
            $sub_detail->save();

            // Save Role (Admin & busines id)
            $role = new Role();
            $role->business_id = $business->business_id;
            $role->role_title = 'Admin';
            $role->save();

            // Save Admin role
            $role_user = new RoleUser();
            $role_user->business_id = $business->business_id;
            $role_user->role_id = $role->role_id;
            $role_user->user_id = $user->user_id;
            $role_user->save();

            // Save Pemision Role 
            // fetch all Permision
            $permission_id = Permission::all()->pluck('permission_module_id');
            foreach ($permission_id as $pId) {
                PermissionRole::create([
                    'business_id' => $business->business_id,
                    'role_id' => $role->role_id,
                    'permission_module_id' => $pId
                ]);
            }

            DB::commit();
            $success = true;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
            DB::rollBack();
            $success = false;
        }

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Set Complete Proceed to Login with Created Credentials',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error Occur Try Again',
            ]);
        }
    }
}
