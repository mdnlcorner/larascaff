<?php

namespace Mulaidarinull\Larascaff\Traits;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

trait HasMedia
{
    public function storeMedia(string $path, string | array $tmpFiles, string $field, string $disk = 'public')
    {
        if (! $tmpFiles) {
            return;
        }

        $media = [];
        if (is_array($tmpFiles)) {
            foreach ($tmpFiles as $tmpFile) {
                $uploadedFile = new File(Storage::disk('local')->path($tmpFile));

                Storage::disk($disk)->putFileAs(
                    path: $path,
                    file: $uploadedFile,
                    name: $uploadedFile->getFilename()
                );

                array_push($media, $uploadedFile->getFilename());
            }

            $this->{$field} = $media;
            $this->save();
        } else {
            $__file = new File(Storage::disk('local')->path($tmpFiles));
            Storage::disk($disk)->putFileAs(
                path: $path,
                file: $__file,
                name: $__file->getFilename()
            );

            $this->{$field} = $__file->getFilename();
            $this->save();
        }
    }

    public function updateMedia(string $path, string | array | null $tmpFiles, string $field, string $disk = 'public')
    {
        if (is_array($tmpFiles)) {
            $media = [];
            foreach ($tmpFiles as $tmpFile) {
                // new file
                if (str_starts_with($tmpFile, 'tmp/')) {
                    $uploadedFile = new File(Storage::disk('local')->path($tmpFile));
                    Storage::disk($disk)->putFileAs(
                        path: $path,
                        file: $uploadedFile,
                        name: $uploadedFile->getFilename()
                    );
                    array_push($media, $uploadedFile->getFilename());

                } else {
                    // existing file
                    array_push($media, $tmpFile);
                }
            }

            $existingMedia = $this->oldModelValue->{$field} ?? [];

            unset($this->oldModelValue);

            $this->{$field} = $media;
            $this->save();

            foreach ($existingMedia as $item) {
                if (! in_array($item, $tmpFiles)) {
                    $this->deleteMedia(str($path)->finish('/') . $item, $disk);
                }
            }
        } else {
            if (is_null($tmpFiles)) {
                if (is_array($this->{$field})) {
                    foreach ($this->{$field} as $media) {
                        $this->deleteMedia(str($path)->finish('/') . $media, $disk);
                    }
                } else {
                    $this->deleteMedia(str($path)->finish('/') . $this->{$field}, $disk);
                }

                unset($this->oldModelValue);

                $this->{$field} = null;
                $this->save();

                return;
            }

            if (str_starts_with($tmpFiles, 'tmp/')) {
                $uploadeFile = new File(Storage::disk('local')->path($tmpFiles));

                Storage::disk($disk)->putFileAs(
                    path: $path,
                    file: $uploadeFile,
                    name: $uploadeFile->getFilename()
                );

                $this->deleteMedia(str($path)->finish('/') . ($this->oldModelValue ?? $this)->{$field}, $disk);

                unset($this->oldModelValue);

                $this->{$field} = $uploadeFile->getFilename();
                $this->save();
            }
        }
    }

    public function deleteMedia($filename = null, string $disk = 'public')
    {
        Storage::disk($disk)->delete($filename);
    }

    public function getMedia(string $field)
    {
        if (is_null($field)) {
            return [];
        }
        
        if (is_string($this->{$field})) {
            return [$this->{$field}];
        }

        return $this->{$field};
    }
}
