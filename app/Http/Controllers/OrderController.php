<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== config('constants.roles.customer')) {
            return ApiResponse::error('Access denied: Only customers can view orders.', 403);
        }

        $orders = $user->customer->orders;

        return ApiResponse::success($orders, 'Orders retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $creds = Validator::make($request->all(), [
            'total_price' => 'required|numeric|min:0.1',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
        ]);

        if ($creds->fails()) {
            return ApiResponse::validationError($creds->errors());
        }

        try {
            $order = Order::create([
                'customer_id' => $user->customer->id,
                'total_price' => $request->total_price,
                'status' => $request->status,
                'items' => $request->items,
            ]);

            return ApiResponse::success($order, 'Order created successfully', 201);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to create order' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
