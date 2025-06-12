<?php

namespace Mulaidarinull\Larascaff\Actions\Concerns;

use Closure;

trait HasLifecycle
{
    protected ?Closure $afterSave = null;

    protected ?Closure $beforeSave = null;

    protected ?Closure $beforeFormFilled = null;

    protected function callHook(?callable $hook)
    {
        if ($hook) {
            $this->resolveClosureParams($hook);
        }
    }

    public function afterSave(Closure $callback): static
    {
        $this->afterSave = $callback;

        return $this;
    }

    public function beforeFormFilled(Closure $callback): static
    {
        $this->beforeFormFilled = $callback;

        return $this;
    }

    public function beforeSave(callable $callback): static
    {
        $this->beforeSave = $callback;

        return $this;
    }
}
