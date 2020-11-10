<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

use App\Http\Controllers\SetupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\MailSettingController;
use App\Http\Controllers\SmsSettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceSettingController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\ProductController;



// Bussines and Account Setup
Route::post('setup', [SetupController::class, 'setup']);

// Account Login
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout']);
// Route::post('register', [UserController::class, 'register']);

// prefix('/admin')->name('admin.')->
Route::group([
    'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'jwt.verify'
], function () {
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('/auth-user', [UserController::class, 'user']);
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user', [UserController::class, 'store']);
    Route::get('/user/{user_id}', [UserController::class, 'show']);
    Route::put('/user/{user_id}', [UserController::class, 'update']);
    Route::delete('/user/{user_id}', [UserController::class, 'destroy']);

    // Category 
    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::get('/category/{category_id}', [CategoryController::class, 'show']);
    Route::put('/category/{category_id}', [CategoryController::class, 'update']);
    Route::delete('/category/{category_id}', [CategoryController::class, 'destroy']);

    // Brand
    Route::get('/brand', [BrandController::class, 'index']);
    Route::post('/brand', [BrandController::class, 'store']);
    Route::get('/brand/{brand_id}', [BrandController::class, 'show']);
    Route::put('/brand/{brand_id}', [BrandController::class, 'update']);
    Route::delete('/brand/{brand_id}', [BrandController::class, 'destroy']);

    // Color
    Route::get('/color', [ColorController::class, 'index']);
    Route::post('/color', [ColorController::class, 'store']);
    Route::get('/color/{color_id}', [ColorController::class, 'show']);
    Route::put('/color/{color_id}', [ColorController::class, 'update']);
    Route::delete('/color/{color_id}', [ColorController::class, 'destroy']);

    // Size
    Route::get('/size', [SizeController::class, 'index']);
    Route::post('/size', [SizeController::class, 'store']);
    Route::get('/size/{size_id}', [SizeController::class, 'show']);
    Route::put('/size/{size_id}', [SizeController::class, 'update']);
    Route::delete('/size/{size_id}', [SizeController::class, 'destroy']);

    // Type
    Route::get('/type', [TypeController::class, 'index']);
    Route::post('/type', [TypeController::class, 'store']);
    Route::get('/type/{type_id}', [TypeController::class, 'show']);
    Route::put('/type/{type_id}', [TypeController::class, 'update']);
    Route::delete('/type/{type_id}', [TypeController::class, 'destroy']);

    // Mail Settings
    Route::get('/mail-setting', [MailSettingController::class, 'index']);
    Route::post('/mail-setting', [MailSettingController::class, 'store']);
    Route::get('/mail-setting/{mail_setting_id}', [MailSettingController::class, 'show']);
    Route::put('/mail-setting/{mail_setting_id}', [MailSettingController::class, 'update']);
    Route::delete('/mail-setting/{mail_setting_id}', [MailSettingController::class, 'destroy']);

    // SMS Settings
    Route::get('/sms-setting', [SmsSettingController::class, 'index']);
    Route::post('/sms-setting', [SmsSettingController::class, 'store']);
    Route::get('/sms-setting/{sms_setting_id}', [SmsSettingController::class, 'show']);
    Route::put('/sms-setting/{sms_setting_id}', [SmsSettingController::class, 'update']);
    Route::delete('/sms-setting/{sms_setting_id}', [SmsSettingController::class, 'destroy']);

    // Supplier Settings
    Route::get('/supplier', [SupplierController::class, 'index']);
    Route::post('/supplier', [SupplierController::class, 'store']);
    Route::get('/supplier/{supplier_id}', [SupplierController::class, 'show']);
    Route::put('/supplier/{supplier_id}', [SupplierController::class, 'update']);
    Route::delete('/supplier/{supplier_id}', [SupplierController::class, 'destroy']);

    // Customer 
    Route::get('/customer', [CustomerController::class, 'index']);
    Route::post('/customer', [CustomerController::class, 'store']);
    Route::get('/customer/{customer_id}', [CustomerController::class, 'show']);
    Route::put('/customer/{customer_id}', [CustomerController::class, 'update']);
    Route::delete('/customer/{customer_id}', [CustomerController::class, 'destroy']);

    // Department 
    Route::get('/department', [DepartmentController::class, 'index']);
    Route::post('/department', [DepartmentController::class, 'store']);
    Route::get('/department/{department_id}', [DepartmentController::class, 'show']);
    Route::put('/department/{department_id}', [DepartmentController::class, 'update']);
    Route::delete('/department/{department_id}', [DepartmentController::class, 'destroy']);

    // Employee 
    Route::get('/employee', [EmployeeController::class, 'index']);
    Route::post('/employee', [EmployeeController::class, 'store']);
    Route::get('/employee/{employee_id}', [EmployeeController::class, 'show']);
    Route::put('/employee/{employee_id}', [EmployeeController::class, 'update']);
    Route::delete('/employee/{employee_id}', [EmployeeController::class, 'destroy']);

    // Attendance Setting 
    Route::get('/attendance-setting', [AttendanceSettingController::class, 'index']);
    Route::post('/attendance-setting', [AttendanceSettingController::class, 'store']);
    Route::get('/attendance-setting/{attendance_settings_id}', [AttendanceSettingController::class, 'show']);
    Route::put('/attendance-setting/{attendance_settings_id}', [AttendanceSettingController::class, 'update']);
    Route::delete('/attendance-setting/{attendance_settings_id}', [AttendanceSettingController::class, 'destroy']);

    // Attendance  
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::post('/attendance', [AttendanceController::class, 'store']);
    Route::get('/attendance/{attendance_id}', [AttendanceController::class, 'show']);
    Route::put('/attendance/{attendance_id}', [AttendanceController::class, 'update']);
    Route::delete('/attendance/{attendance_id}', [AttendanceController::class, 'destroy']);

    // Role  
    Route::get('/role', [RoleController::class, 'index']);
    Route::post('/role', [RoleController::class, 'store']);
    Route::get('/role/{role_id}', [RoleController::class, 'show']);
    Route::put('/role/{role_id}', [RoleController::class, 'update']);
    Route::delete('/role/{role_id}', [RoleController::class, 'destroy']);

    // Role User
    Route::get('/role-user', [RoleUserController::class, 'index']);
    Route::post('/role-user', [RoleUserController::class, 'store']);
    Route::get('/role-user/{role_user_id}', [RoleUserController::class, 'show']);
    Route::put('/role-user/{role_user_id}', [RoleUserController::class, 'update']);
    Route::delete('/role-user/{role_user_id}', [RoleUserController::class, 'destroy']);


    // Product
    Route::get('/product', [ProductController::class, 'index']);
    Route::post('/product', [ProductController::class, 'store']);
    Route::get('/product/{product_id}', [ProductController::class, 'show']);
    Route::put('/product/{product_id}', [ProductController::class, 'update']);
    Route::delete('/product/{product_id}', [ProductController::class, 'destroy']);
});
