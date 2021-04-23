<?php

namespace Helium\Config\Table\Filter;

use Helium\Config\Table\Filter\Filter;
use Helium\Table\DefaultSearchHandler;

class Search extends Filter
{
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'columns':
                return [];
            case 'slug':
                return 'search';
            case 'filterHandler':
                return DefaultSearchHandler::class;
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
