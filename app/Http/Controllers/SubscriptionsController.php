<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionsModel;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptions = SubscriptionsModel::all();

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $subscriptions
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $subscription = SubscriptionsModel::create($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Subscription created successfully',
            'data' => $subscription
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subscription = SubscriptionsModel::find($id);

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $subscription
        ]);
    }
    public function imageUpload(Request $request)
    {
        // Check if a file was uploaded
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $file = $request->file('image');

            // Get the original file name
            $originalFileName = $file->getClientOriginalName();

            // Set the destination path
            $destinationPath = public_path('uploads/images');

            // Move the file to the destination path
            $file->move($destinationPath, $originalFileName);

            // Generate the URL to the stored file
            $url = asset('uploads/images/' . $originalFileName);

            return response()->json([
                'status' => 200,
                'message' => 'Image uploaded successfully',
                'data' => $url
            ], 200);
        }

        // If no file was uploaded, return an error response
        return response()->json([
            'status' => 400,
            'message' => 'No image uploaded',
            'data' => []
        ], 400);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subscription = SubscriptionsModel::find($id);

        $subscription->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Subscription updated successfully',
            'data' => $subscription
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subscription = SubscriptionsModel::find($id);

        $subscription->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Subscription deleted successfully'
        ], 200);
    }
}
