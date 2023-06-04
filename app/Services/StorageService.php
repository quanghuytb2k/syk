<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageService
{
    /**
     * @param $files
     * @return array
     */
    public function uploadFiles($files): array
    {
        $pathFiles = [];
        foreach ($files as $file)
        {
            $path = Carbon::now()->format('Y-m-d') .'/'. Auth::user()->id;
            $fileName = Carbon::now()->getTimestamp() . '_' . Str::random(10) . '.' . $file->extension();
            $pathFiles[] = [
                'name' => $fileName,
                'path' => Storage::disk('s3')->putFileAs($path, new File($file), $fileName)
            ];
        }

        return $pathFiles;
    }
}
