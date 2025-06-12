<?php

namespace Mulaidarinull\Larascaff\Traits;

use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

trait HasMedia
{
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * @return MorphOne<Media, $this>
     */
    public function singleMedia(): MorphOne
    {
        return $this->morphOne(Media::class, 'model');
    }

    public function storeMedia(string $path, string | array $tmpFiles, ?string $field = null, string $disk = 'public')
    {
        if (! $tmpFiles) {
            return;
        }

        $fileData = [];
        if (is_array($tmpFiles)) {
            foreach ($tmpFiles as $tmpFile) {
                $__file = new File(Storage::disk('local')->path($tmpFile));

                Storage::disk($disk)->putFileAs(
                    path: $path,
                    file: $__file,
                    name: $__file->getFilename()
                );

                if (! $field) {
                    array_push($fileData, new Media([
                        'filename' => $__file->getFilename(),
                        'path' => $path,
                        'extension' => $__file->getExtension(),
                    ]));
                } else {
                    array_push($fileData, $__file->getFilename());
                }
            }
            if (! $field) {
                $this->media()->saveMany($fileData);
            } else {
                $this->{$field} = $fileData;
                $this->save();
            }
        } else {
            $__file = new File(Storage::disk('local')->path($tmpFiles));
            Storage::disk($disk)->putFileAs(
                path: $path,
                file: $__file,
                name: $__file->getFilename()
            );

            if (! $field) {
                $this->media()->create([
                    'filename' => $__file->getFilename(),
                    'path' => $path,
                    'extension' => $__file->getExtension(),
                ]);
            } else {
                $this->{$field} = $__file->getFilename();
                $this->save();
            }
        }
    }

    public function updateMedia(string $path, string | array | null $tmpFiles, ?string $field = null, string $disk = 'public')
    {
        $existingMedia = $this->getMediaUrl();
        if (is_array($tmpFiles)) {
            $media = [];
            foreach ($tmpFiles as $tmpFile) {
                // new file
                if (str_starts_with($tmpFile, 'tmp/')) {
                    $__file = new File(Storage::disk('local')->path($tmpFile));
                    Storage::disk($disk)->putFileAs(
                        path: $path,
                        file: $__file,
                        name: $__file->getFilename()
                    );
                } else {
                    // existing file
                    $__file = new File(Storage::disk($disk)->path($path . '/' . $tmpFile));
                }
                array_push($media, new Media([
                    'filename' => $__file->getFilename(),
                    'path' => $path,
                    'extension' => $__file->getExtension(),
                ]));
            }

            // store new file or edit file
            if (! $field) {
                $this->media()->delete();
                if (count($media)) {
                    $this->media()->saveMany($media);
                }
            } else {
                $this->{$field} = $__file->getFilename();
                $this->save();
            }

            $oldFiles = array_filter($tmpFiles, fn ($item) => ! str_starts_with($item, 'tmp/'));
            foreach ($existingMedia as $existing) {
                if (! in_array($existing, $oldFiles)) {
                    $this->deleteMedia($existing, $field);
                }
            }
        } else {
            if (is_null($tmpFiles)) {
                if ($field) {
                    $this->deleteMedia($path . '/' . $this->{$field}, $field);
                } else {
                    if ($this->singleMedia?->filename) {
                        foreach ($this->media as $media) {
                            $this->deleteMedia($media->filename, $field);
                        }
                    }
                }

                return;
            }
            if (str_starts_with($tmpFiles, 'tmp/')) {
                $__file = new File(Storage::disk('local')->path($tmpFiles));
                Storage::disk($disk)->putFileAs(
                    path: $path,
                    file: $__file,
                    name: $__file->getFilename()
                );
                if (! $field) {
                    // delete old image if exist
                    if ($this->singleMedia) {
                        $this->deleteMedia($this->singleMedia->filename, $field);
                    }
                    $this->media()->create([
                        'filename' => $__file->getFilename(),
                        'path' => $path,
                        'extension' => $__file->getExtension(),
                    ]);
                } else {
                    $this->deleteMedia($path . '/' . ($this->oldModelValue ?? $this)->{$field}, $field);
                    unset($this->oldModelValue);
                    $this->{$field} = $__file->getFilename();
                    $this->save();
                }
            }
        }
    }

    public function deleteMedia($filename = null, ?string $field = null, string $disk = 'public')
    {
        // if filename is null, delete all image
        if (! $filename) {
            foreach ($this->media as $media) {
                Storage::disk($disk)->delete($media->path . '/' . $media->filename);
            }
            $this->media()->delete();
        } else {
            if (! $field) {
                $explode = explode('/', $filename);
                $media = $this->media->where('filename', $explode[count($explode) - 1])->first();
                if ($media) {
                    Storage::disk($disk)->delete($media->path . '/' . $filename);
                    $media->delete();
                }
            } else {
                Storage::disk($disk)->delete($filename);
            }
        }
    }

    public function getMediaUrl(?string $field = null, string $disk = 'public')
    {
        if (! $field) {
            return $this->media->filter(function ($item) use ($disk) {
                $isExist = Storage::disk($disk)->fileExists("{$item->path}/$item->filename");
                if (! $isExist) {
                    $item->delete();
                }

                return $isExist;
            })->values()->map(function ($item) {
                return $item->filename;
            })->toArray();
        }
        if (is_string($this->{$field})) {
            return [$this->{$field}];
        }

        return $this->{$field};
    }
}
