<?php

namespace Helium\Config\Table\Filter;

use Illuminate\Support\Arr;
use Helium\Config\Table\Filter\Filter;

class Search extends Filter
{
    public function getLabel(): ?string
    {
        return Arr::get('placeholder', $this->fieldConfig, null);
    }

    public function getPlaceholder(): ?string
    {
        return Arr::get('placeholder', $this->fieldConfig, 'Search');
    }

    public function getPrefix(): ?string
    {
        return Arr::get('prefix', $this->fieldConfig, '<i class="fas fa-search" aria-hidden="true"></i>');
    }
}
