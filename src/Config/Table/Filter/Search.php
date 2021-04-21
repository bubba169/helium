<?php

namespace Helium\Config\Table\Filter;

use Helium\Config\Table\Filter\Filter;

class Search extends Filter
{
    /**
     * {@inheritDoc}
     */
    protected function defaultLabel(): ?string
    {
        return null;
    }

    protected function defaultPlaceholder(): ?string
    {
        return 'Search';
    }

    protected function defaultPrefix(): ?string
    {
        return '<i class="fas fa-search" aria-hidden="true"></i>';
    }
}
