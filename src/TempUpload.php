<?php

namespace Mulaidarinull\Larascaff;

use Illuminate\Http\Request;

class TempUpload
{
    public function __invoke(Request $request)
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
}
