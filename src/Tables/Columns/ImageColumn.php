<?php

namespace Mulaidarinull\Larascaff\Tables\Columns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Mulaidarinull\Larascaff\Forms\Concerns\HasMedia;

class ImageColumn extends Column
{
    use HasMedia;

    protected ?Model $record = null;

    protected ?bool $circle = null;

    protected ?int $imageWidth = null;

    protected ?int $imageHeight = null;

    protected ?int $imageSize = null;

    public static function make(array | string $data = [], string $name = ''): static
    {
        $static = parent::make($data, $name);
        $static->orderable(false);
        $static->searchable(false);

        return $static;
    }

    public function imageSize(int $imageSize): static
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    public function imageWidth(int $imageWidth): static
    {
        $this->imageWidth = $imageWidth;

        return $this;
    }

    public function imageHeight(int $imageHeight): static
    {
        $this->imageHeight = $imageHeight;

        return $this;
    }

    public function circle(bool $status = true): static
    {
        $this->circle = $status;

        return $this;
    }

    public function record(Model $record): static
    {
        $this->record = $record;

        return $this;
    }

    public function view(): ?View
    {
        if (!$this->record->{$this->name}) {
            return null;
        }

        $baseUrl = Storage::disk($this->getDisk())->url(str($this->getPath())->finish('/'));

        if (is_array($this->record->{$this->name})) {
            foreach ($this->record->{$this->name} as $record) {
                $sources[] = $baseUrl . $record;
            }
        } else {
            $sources = [$baseUrl . $this->record?->{$this->name}];
        }

        return view('larascaff::image', [
            'sources' => $sources,
            'circle' => $this->circle,
            'imageSize' => $this->imageSize,
            'imageWidth' => $this->imageWidth,
            'imageHeight' => $this->imageHeight,
        ]);
    }
}
