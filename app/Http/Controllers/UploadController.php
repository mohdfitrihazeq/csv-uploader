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
        $request->validate(['csv_file' => 'required|mimes:csv,txt']);
    
        $file = $request->file('csv_file');
        $originalName = $file->getClientOriginalName();
        $path = $file->store('uploads');
    
        $upload = Upload::create([
            'file_path' => $path,
            'file_name' => $originalName,
            'status' => 'pending',
            'uploaded_at' => now(),
        ]);
    
        ProcessCsv::dispatch($upload);
    
        return redirect()->route('uploads.index');
    }
    
    public function status()
    {
        return Upload::latest()->get(['id', 'file_name', 'file_path', 'status', 'created_at']);
    }
    
}