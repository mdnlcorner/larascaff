<?php

namespace Mulaidarinull\Larascaff\Forms\Components;

use Illuminate\Support\Facades\Blade;
use Mulaidarinull\Larascaff\Forms\Concerns;

class Section extends Layout
{
    use Concerns\HasCollapsible;
    use Concerns\HasColumnSpan;
    use Concerns\HasComponent;
    use Concerns\HasModule;
    use Concerns\HasRelationship;

    protected ?string $description = null;

    public function __construct()
    {
        $this->columnSpan = 'full';
    }

    public function description(string $description)
    {
        $this->description = $description;

        return $this;
    }

    public function view()
    {
        $slot = '';
        foreach ($this->components as $component) {
            if (method_exists($component, 'module')) {
                $component->module($this->module);
            }
            $slot .= $component->view();
        }

        return Blade::render(
            <<<'HTML'
            <x-larascaff::layouts.section 
                :name="$name"
                :columnSpan="$columnSpan"
                :columns="$columns"
                :description="$description"
                :collapsible="$collapsible"
                :collapsed="$collapsed"
            >{!! $slot !!}</x-larascaff::layouts.section>
            HTML,
            [
                'name' => $this->name,
                'columnSpan' => $this->columnSpan,
                'columns' => $this->columns,
                'description' => $this->description,
                'collapsible' => $this->collapsible,
                'collapsed' => $this->collapsed,
                'slot' => $slot,
            ]
        );
    }
}
