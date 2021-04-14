<?php

namespace Helium\Table;

class DefaultListingHandler
{
    public function __invoke(array $config)
    {
        return $config['model']::query();
    }
}
