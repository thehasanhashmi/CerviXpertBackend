<?php

namespace App\Http\Controllers;

use App\Models\PaymentSettingsModel;
use Illuminate\Http\Request;

class PaymentSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payment = PaymentSettingsModel::all();

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $payment
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $payment = PaymentSettingsModel::create($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Payment settings created successfully',
            'data' => $payment
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = PaymentSettingsModel::find($id);

        return response()->json([
            'status' => 200,
            'message' => 'Payment settings retrieved successfully',
            'data' => $payment
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = PaymentSettingsModel::find($id);

        $payment->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Payment settings updated successfully',
            'data' => $payment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = PaymentSettingsModel::find($id);

        $payment->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Payment settings deleted successfully'
        ], 200);
    }
}
