<?php

namespace App\Http\Controllers;

use App\Models\CvmiTestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CvmiTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cvmi = CvmiTestModel::all();

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $cvmi
        ],);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'user_id' => 'required|integer',
            'testing_file' => 'required|file|mimes:pdf,doc,docx,jpg,png', // Adjust file types as needed
            'stage' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Handle the file upload
        if ($request->hasFile('testing_file')) {
            // Get the file from the request
            $file = $request->file('testing_file');

            // Define the file name and path
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/testing_files'), $fileName);
            $filePath = 'uploads/testing_files/' . $fileName;

            // Prepare the data for insertion into the database
            $data = $request->all();
            $data['testing_file'] = $filePath;

            // Create the record in the database
            $cvmi = CvmiTestModel::create($data);

            // Return the response
            return response()->json([
                'status' => 200,
                'message' => 'Data created successfully',
                'data' => $cvmi
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'File not uploaded',
            ], 400);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cvmi = CvmiTestModel::find($id);

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $cvmi
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function updatedata(Request $request, string $id)
    {
        // Find the record by ID
        $cvmi = CvmiTestModel::find($id);
    
        // If record not found, return error response
        if (!$cvmi) {
            return response()->json([
                'status' => 404,
                'message' => 'Record not found',
            ], 404);
        }
    
        // Validate the incoming request
        $request->validate([
            'user_id' => 'required|integer',
            'stage' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'testing_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png', // Adjust file types as needed
        ]);
    
        // Handle the file upload if new file provided
        if ($request->hasFile('testing_file')) {
            // Get the file from the request
            $file = $request->file('testing_file');
    
            // Define the file name and path
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/testing_files'), $fileName);
            $filePath = 'uploads/testing_files/' . $fileName;
    
            // Delete the previous file if it exists
            if ($cvmi->testing_file) {
                // Remove the old file from the storage
                $oldFilePath = public_path($cvmi->testing_file);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
    
            // Update the testing_file field with the new file path
            $cvmi->testing_file = $filePath;
        }
    
        // Update other fields
        $cvmi->user_id = $request->user_id;
        $cvmi->stage = $request->stage;
        $cvmi->description = $request->description;
    
        // Save the updated record
        $cvmi->save();
    
        // Return success response
        return response()->json([
            'status' => 200,
            'message' => 'Record updated successfully',
            'data' => $cvmi,
        ]);
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the record by ID
        $cvmi = CvmiTestModel::find($id);
        $cvmi->delete();

        // Return success response
        return response()->json([
            'status' => 200,
            'message' => 'Record deleted successfully',
        ]);
    }
}
