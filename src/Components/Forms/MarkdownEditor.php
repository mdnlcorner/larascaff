<?php

namespace Mulaidarinull\Larascaff\Components\Forms;

use Illuminate\Support\Facades\Blade;

class MarkdownEditor extends Field
{
    protected array $toolbar = [
        'bold',
        'italic',
        'strikethrough',
        'link',
        'heading',
        'quote',
        'unordered-list',
        'ordered-list',
        'code',
        'upload-image',
        'undo',
        'redo',
        'drawtable',
    ];

    protected string $imagePath = 'images';

    protected int $imageMaxSize = 2048;

    public function imageMaxSize(string $imageMaxSize)
    {
        $this->imageMaxSize = $imageMaxSize;

        return $this;
    }

    public function imagePath(string $imagePath)
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function toolbar(array $toolbar)
    {
        $this->toolbar = $toolbar;

        return $this;
    }

    public function disableToolbar(array $disable)
    {
        $newToolbar = [];
        foreach ($this->toolbar as $toolbar) {
            if (! in_array($toolbar, $disable)) {
                $newToolbar[] = $toolbar;
            }
        }
        $this->toolbar = $newToolbar;

        return $this;
    }

    public function view()
    {
        if (is_null($this->value)) {
            $this->value = getRecord($this->name);
        }

        return Blade::render(
            <<<'HTML'
            <x-larascaff::forms.markdown-editor 
                :columnSpan="$columnSpan" 
                :name="$name" 
                :disabled="$disabled" 
                :readonly="$readonly" 
                :label="$label" 
                :value="$value" 
                :placeholder="$placeholder" 
                :toolbar="$toolbar" 
                :imagePath="$imagePath" 
                :imageMaxSize="$imageMaxSize" 
            />
            HTML,
            [
                'name' => $this->name,
                'disabled' => $this->disabled,
                'label' => $this->label,
                'placeholder' => $this->placeholder,
                'value' => $this->value,
                'readonly' => $this->readonly,
                'columnSpan' => $this->columnSpan,
                'toolbar' => $this->toolbar,
                'imagePath' => $this->imagePath,
                'imageMaxSize' => $this->imageMaxSize,
            ]
        );
    }
}
