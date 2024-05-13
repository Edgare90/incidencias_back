<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

// crear link : php artisan storage:link//

class StorageController extends Controller
{
    public function getImage($filename)
    {
        $path = public_path('archivos/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }
}
