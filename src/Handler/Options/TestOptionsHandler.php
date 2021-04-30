<?php

namespace Helium\Handler\Options;

class TestOptionsHandler
{
    public function __invoke()
    {
        return [
            '1' => 'Option 1',
            '2' => 'Option 2',
            '3' => 'Option 3'
        ];
    }
}
