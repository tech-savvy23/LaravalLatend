<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends Controller
{
    public function delete()
    {
        $filename = request('name');
        $media    = Media::whereName($filename)->first();
        if (!$media) {
            return response('File Not Found', Response::HTTP_NOT_FOUND);
        };
        $media->delete();
        $disk  = env('DISK', 'public');
        Storage::disk($disk)->delete($filename);
        return response('deleted', Response::HTTP_NO_CONTENT);
    }
}
