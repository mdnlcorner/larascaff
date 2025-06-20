<?php

namespace Mulaidarinull\Larascaff\Pages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Uploader
{


    public function tempUploadHandler(Request $request)
    {
        /** @var UploadedFile[] $files */
        $files = $request->allFiles();

        if (empty($files)) {
            abort(422, 'No files were uploaded.');
        }

        if (count($files) > 1) {
            abort(422, 'Only 1 file can be uploaded at a time.');
        }

        $requestKey = array_key_first($files);

        /**
         * @var UploadedFile $file
         */
        $file = is_array($request->input($requestKey))
            ? $request->file($requestKey)[0]
            : $request->file($requestKey);

        // Store the file in a temporary location and return the location
        // for FilePond to use.
        $filename = $file->store(
            path: 'tmp'
        );

        return response()->json(['filename' => $filename]);
    }

    public function uploadHandler(Request $request)
    {
        /** @var UploadedFile[] $files */
        $files = $request->allFiles();

        foreach ($files as $name => $file) {
            $request->validate([
                $name => 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
            ]);
        }
        if (empty($files)) {
            abort(422, 'No files were uploaded.');
        }

        if (count($files) > 1) {
            abort(422, 'Only 1 file can be uploaded at a time.');
        }

        $requestKey = array_key_first($files);

        $file = is_array($request->input($requestKey))
            ? $request->file($requestKey)[0]
            : $request->file($requestKey);

        $filename = Storage::disk(config('larascaff.default_filesystem_disk'))->put($request->path, $file);

        return response()->json(['filename' => $filename]);
    }
}