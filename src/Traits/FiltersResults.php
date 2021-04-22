<?php

namespace Helium\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FiltersResults
{
    /**
     * {@inheritDoc}
     */
    public function getFilterHandler(): string
    {
        return Arr::get('filterHandler', $this->fieldConfig, DefaultFilterHandler::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getPlaceholder(): ?string
    {
        return Arr::get(
            'placeholder',
            $this->fieldConfig,
            'Filter By ' . Str::title(str_humanise($this->slug))
        );
    }
}
