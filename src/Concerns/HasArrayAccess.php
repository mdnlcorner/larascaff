<?php

namespace Mulaidarinull\Larascaff\Concerns;

trait HasArrayAccess
{
    protected array $options = [];

    /**
     * Determine if an item exists at an offset.
     *
     * @param  TKey  $key
     */
    public function offsetExists($key): bool
    {
        return isset($this->options[$key]);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  TKey  $key
     * @return TValue
     */
    public function offsetGet($key): mixed
    {
        return $this->options[$key];
    }

    /**
     * Set the item at a given offset.
     *
     * @param  TKey|null  $key
     * @param  TValue  $value
     */
    public function offsetSet($key, $value): void
    {
        if (is_null($key)) {
            $this->options[] = $value;
        } else {
            $this->options[$key] = $value;
        }
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  TKey  $key
     */
    public function offsetUnset($key): void
    {
        unset($this->options[$key]);
    }
}
