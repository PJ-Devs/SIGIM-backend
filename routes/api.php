<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\{
    AuthController,
    ProductController,
    EnterpriseController,
    InvoiceController,
    PermissionController,
    SaleController,
    RoleController,
    CategoryController,
    ClientController,
    SupplierController,
    UserController
};

/**
 * AuthController routes
 * 1. mobileTokenBasedLogin - POST /auth/token
 * 2. signUp - POST /auth/signup
 * 3. logOut - POST /auth/logout
 */
Route::post('/auth/token', [AuthController::class, 'mobileTokenBasedLogin']);
Route::post('/auth/signup', [AuthController::class, 'signUp']);
Route::post('/auth/logout', [AuthController::class, 'logOut']);

/**
 * User routes
 * 1. Get profile - GET /profile
 * 2. Update profile - PUT /profile
 * 3. index - GET /users
 * 4. show - GET /users/{id}
 * 5. store - POST /users
 * 6. update - PUT /users/{id}
 * 7. destroy - DELETE /users/{id}
 */
Route::apiResource('users', UserController::class);
Route::get('/profile', [UserController::class, 'showProfile']);
Route::put('/profile', [UserController::class, 'updateProfile']);
Route::get('/enterprise', [UserController::class, 'getMyEnterprise']);

Route::apiResource('products', ProductController::class);

Route::apiResource('enterprises', EnterpriseController::class);

Route::apiResource('invoices', InvoiceController::class);
Route::apiResource('permissions', PermissionController::class);
Route::apiResource('sales', SaleController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('clients', ClientController::class);
Route::apiResource('suppliers', SupplierController::class);
