<?php

namespace App\Http\Controllers;

use App\Models\CvmiStagesDetailsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CvmiStagesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stages = CvmiStagesDetailsModel::all();
        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $stages
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'stage_name' => 'required|string|max:255',
            'stage_file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx',
            'stage_descrption' => 'nullable|string',
            'more_detailed_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        // Handle single file upload
        if ($request->hasFile('stage_file')) {
            $file = $request->file('stage_file');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = $originalName . '.' . $extension;
            $file->move(public_path('uploads/testing_files'), $fileName);
            $stageFilePath = 'uploads/testing_files/' . $fileName;
        }

        // Handle multiple file uploads
        $detailedFilePaths = [];
        if ($request->hasFile('more_detailed_files')) {
            foreach ($request->file('more_detailed_files') as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileName = $originalName . '.' . $extension;
                $file->move(public_path('uploads/detailed_files'), $fileName);
                $detailedFilePaths[] = 'uploads/detailed_files/' . $fileName;
            }
        }

        $stage = CvmiStagesDetailsModel::create([
            'stage_name' => $request->input('stage_name'),
            'stage_file' => $stageFilePath ?? null,
            'stage_descrption' => $request->input('stage_descrption'),
            'more_detailed_files' => json_encode($detailedFilePaths),
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Data created successfully',
            'data' => $stage
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stage = CvmiStagesDetailsModel::find($id);
        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $stage
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateData(Request $request, string $id)
    {
        $request->validate([
            'stage_name' => 'required|string|max:255',
            'stage_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
            'stage_descrption' => 'nullable|string',
            'more_detailed_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        $stage = CvmiStagesDetailsModel::findOrFail($id);

        // Handle single file upload
        if ($request->hasFile('stage_file')) {
            $file = $request->file('stage_file');
            // $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $fileName = $file->getFilename() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/stage_files'), $fileName);
            $stageFilePath = 'uploads/stage_files/' . $fileName;

            // Delete the old file if exists
            if ($stage->stage_file) {
                $oldFilePath = public_path($stage->stage_file);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $stage->stage_file = $stageFilePath;
        }

        // Handle multiple file uploads
        if ($request->hasFile('more_detailed_files')) {
            // Delete the old files if exists
            if ($stage->more_detailed_files) {
                $oldFiles = json_decode($stage->more_detailed_files, true);
                foreach ($oldFiles as $file) {
                    $oldFilePath = public_path($file);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
            }

            $detailedFilePaths = [];
            foreach ($request->file('more_detailed_files') as $file) {
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/detailed_files'), $fileName);
                $detailedFilePaths[] = 'uploads/detailed_files/' . $fileName;
            }
            $stage->more_detailed_files = json_encode($detailedFilePaths);
        }

        $stage->stage_name = $request->input('stage_name');
        $stage->stage_descrption = $request->input('stage_descrption');
        $stage->save();


        return response()->json([
            'status' => 200,
            'message' => 'Data updated successfully',
            'data' => $stage
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stage = CvmiStagesDetailsModel::findOrFail($id);

        // Delete the single file
        if ($stage->stage_file) {
            Storage::delete($stage->stage_file);
        }

        // Delete the multiple files
        if ($stage->more_detailed_files) {
            $files = json_decode($stage->more_detailed_files, true);
            foreach ($files as $file) {
                Storage::delete($file);
            }
        }

        $stage->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data Deleted successfully',
        ]);
    }
}
