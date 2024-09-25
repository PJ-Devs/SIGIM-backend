<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\{ProductController, EnterpriseController, InvoiceController, PermissionController,
SaleController, RoleController, CategoryController, ClientController, SupplierController};

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('products', ProductController::class);
Route::apiResource('enterprises', EnterpriseController::class);
Route::apiResource('invoices', InvoiceController::class);
Route::apiResource('permissions', PermissionController::class);
Route::apiResource('sales', SaleController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('clients', ClientController::class);
Route::apiResource('suppliers', SupplierController::class);
