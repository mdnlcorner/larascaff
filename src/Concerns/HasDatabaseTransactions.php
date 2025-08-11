<?php

namespace Mulaidarinull\Larascaff\Concerns;

trait HasDatabaseTransactions
{
    protected bool $databaseTransaction = false;

    public function databaseTransactions(?bool $status = true): static
    {
        $this->databaseTransaction = $status;

        return $this;
    }

    public function isDatabaseTransactions(): bool
    {
        return $this->databaseTransaction;
    }
}
