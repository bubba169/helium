<?php

namespace Helium\Config\Table\Filter;

use Helium\Config\Table\Filter\Filter;
use Helium\Handler\Filter\SearchHandler;

class SearchFilter extends Filter
{
    /**
     * {@inheritDoc}
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'value':
                return '{request.search}';
            case 'columns':
                return [];
            case 'slug':
                return 'search';
            case 'handler':
                return SearchHandler::class;
            case 'label':
                return null;
            case 'placeholder':
                return 'Search';
            case 'prefix':
                return '<i class="fas fa-search" aria-hidden="true"></i>';
        }

        return parent::getDefault($key);
    }
}
