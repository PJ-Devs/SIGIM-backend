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
    UserController,
    OTPController,
    CartController
};

/**
 * AuthController routes
 * 1. mobileTokenBasedLogin - POST /auth/token
 * 2. signUp - POST /auth/signup
 * 3. logOut - POST /auth/logout
 * 4. resetPassword - POST /auth/password-reset
 */

Route::post('/auth/token', [AuthController::class, 'mobileTokenBasedLogin']);
Route::post('/auth/signup', [AuthController::class, 'signUp']);
Route::post('/auth/logout', [AuthController::class, 'logOut']);
Route::post('/auth/password-reset', [AuthController::class, 'resetPassword']);

/**
 * OTPController routes
 * 1. Generate password reset OTP - POST /otp/password-reset
 * 2. Verify password reset OTP - POST /otp/password-reset/verify
 */
Route::post('/otp/password-reset', [OTPController::class, 'generatePasswordResetOTP']);
Route::post('/otp/password-reset/verify', [OTPController::class, 'verifyPasswordResetOT']);

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

/**
 * ProductController routes
 * 1. index - GET /products
 * 2. show - GET /products/{id}
 * 3. store - POST /products
 * 4. update - PUT /products/{id}
 * 5. destroy - DELETE /products/{id}
 */
Route::apiResource('/products', ProductController::class);

/**
 * EnterpriseController routes
 * 1. index - GET /enterprises
 * 2. show - GET /enterprises/{id}
 * 3. store - POST /enterprises
 * 4. update - PUT /enterprises/{id}
 * 5. destroy - DELETE /enterprises/{id}
 */
Route::apiResource('enterprises', EnterpriseController::class);

Route::apiResource('invoices', InvoiceController::class);
Route::apiResource('permissions', PermissionController::class);
Route::apiResource('sales', SaleController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('clients', ClientController::class);
Route::apiResource('suppliers', SupplierController::class);

/**
 * Cart routes
 * 1. attachProduct - POST /cart/attach-product
 * 2. detachProduct - POST /cart/detach-product
 * 3. getProducts - GET /cart/products
 * 4. updateProductQuantity - POST /cart/update-product-quantity
 * 5. cleanProducts - POST /cart/clean-products
 */

Route::post('/cart/attach-product', [CartController::class, 'attachProduct']);
Route::post('/cart/detach-product', [CartController::class, 'detachProduct']);
Route::get('/cart/products', [CartController::class, 'getProducts']);
Route::post('/cart/update-product-quantity', [CartController::class, 'updateProductQuantity']);
Route::post('/cart/clean-products', [CartController::class, 'cleanProducts']);
