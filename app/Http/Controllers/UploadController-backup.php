<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upload;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessCsv;
use App\Http\Resources\UploadResource;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    public function index()
    {
        $uploads = Upload::latest()->get();
        return view('upload', compact('uploads'));
    }

    public function store(Request $request)
    {
        // $request->validate(['csv_file' => 'required|mimes:csv,txt']);

        // $path = $request->file('csv_file')->store('uploads');

        // $upload = Upload::create([
        //     'file_path' => $path,
        //     'status' => 'pending',
        //     'uploaded_at' => now(),
        // ]);

        // ProcessCsv::dispatch($upload);
        // $stream = file_get_contents($request->file('csv_file')->getRealPath());
        // $hash = md5($stream);

        // // Check if this file has already been uploaded
        // $existing = Upload::where('file_hash', $hash)->first();
        // if ($existing) {
        //     return redirect()->route('uploads.index')->with('message', 'Duplicate file. Already uploaded.');
        // }

        // $path = $request->file('csv_file')->store('uploads');

        // $upload = Upload::create([
        //     'file_path' => $path,
        //     'file_hash' => $hash,
        //     'status' => 'pending',
        //     'uploaded_at' => now(),
        // ]);

        // ProcessCsv::dispatch($upload);

        // return redirect()->route('uploads.index');
        try {
            $stream = file_get_contents($request->file('csv_file')->getRealPath());
            $hash = md5($stream);

            $existing = Upload::where('file_hash', $hash)->first();
            if ($existing) {
                return redirect()->route('uploads.index')->with('message', 'Duplicate file. Already uploaded.');
            }

            $path = $request->file('csv_file')->store('uploads');

            $upload = Upload::create([
                'file_path' => $path,
                'file_hash' => $hash,
                'status' => 'pending',
                'uploaded_at' => now(),
            ]);

            ProcessCsv::dispatch($upload);

            return redirect()->route('uploads.index')->with('message', 'File uploaded successfully.');

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            Upload::create([
                'file_path' => $request->file('csv_file')->store('uploads'),
                'file_hash' => md5(file_get_contents($request->file('csv_file')->getRealPath())),
                'status' => 'failed',
                'failure_reason' => $e->getMessage(), 
                'uploaded_at' => now(),
            ]);

            return redirect()->route('uploads.index')->with('error', 'File upload failed. Please try again.');
        }
    }

    public function status()
    {
        $uploads = Upload::latest()->limit(20)->get()->map(function ($upload) {
            return [
                'id' => $upload->id,
                'file_path' => $upload->file_path,
                'status' => $upload->status,
                'failure_reason' => $upload->failure_reason,
                'created_at' => $upload->created_at->toIso8601String(),
            ];
        });
    
        return response()->json($uploads);
    }
    
}
