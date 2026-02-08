<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
class ApiFileController extends Controller
{
public function download(File $file)
{
    if (! Storage::exists($file->url)) {
        abort(404, 'File not found');
    }

    return Storage::download($file->url);
}
}
