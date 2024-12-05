<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\helpers\RoleAuthorizationHelper;
use Illuminate\Routing\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;

class SupplierController extends Controller
{
    protected $roleAuthorizationHelper;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->roleAuthorizationHelper = new RoleAuthorizationHelper();
    }

    /**
     * Muestra una lista de proveedores.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'supplier.read')) {
            return response()->json([
                'message' => 'No estás autorizado para realizar esta acción.'
            ], 401);
        }

        $suppliers = Supplier::where('enterprise_id', $user->enterprise_id)->get();
        return response()->json(['data' => $suppliers], 200);
    }

    /**
     * Almacena un nuevo proveedor en el sistema.
     *
     * @param  \App\Http\Requests\StoreSupplierRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSupplierRequest $request)
    {
        $user = $request->user();
        $enterprise = $user->enterprise_id;
        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'supplier.create')) {
            return response()->json([
                'message' => 'No estás autorizado para realizar esta acción.'
            ], 401);
        }

        $supplier = Supplier::create([
            "name" => $request->name,
            "email"=> $request->email,
            "phone_number" => $request->phone_number,
            "NIT" => $request->NIT,
            "enterprise_id" => $enterprise
        ]);
        return response()->json(['data' => $supplier], 201);
    }

    /**
     * Muestra un proveedor específico.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $id)
    {
        $user = $request->user();
        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'supplier.read')) {
            return response()->json([
                'message' => 'No estás autorizado para realizar esta acción.'
            ], 401);
        }

        $supplier = Supplier::find($id);
        return response()->json(['data' => $supplier], 200);
    }

    /**
     * Actualiza un proveedor existente en el sistema.
     *
     * @param  \App\Http\Requests\UpdateSupplierRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSupplierRequest $request, string $id)
    {
        $user = $request->user();
        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'supplier.update')) {
            return response()->json([
                'message' => 'No estás autorizado para realizar esta acción.'
            ], 401);
        }

        $supplier = Supplier::find($id);
        $supplier->update($request->all());
        return response()->json(['data' => $supplier], 200);
    }

    /**
     * Elimina un proveedor del sistema.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Supplier $supplier)
    {
        $user = $request->user();
        if (!$this->roleAuthorizationHelper->hasPermission($user->role, 'supplier.delete')) {
            return response()->json([
                'message' => 'No estás autorizado para realizar esta acción.'
            ], 401);
        }

        $supplier->delete();
        return response(null, 204);
    }
}
