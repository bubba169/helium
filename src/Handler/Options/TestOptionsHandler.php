<?php

namespace Helium\Handler\Options;

class TestOptionsHandler
{
    public function __invoke()
    {
        return [
            'Hello' => 'hi',
            'So Long and thanks for all the fish' => 'bye',
            'Fred' => 'fred',
            'Bob' => 'bob',
            'Harrison' => 'Ford',
            'George' => 'michael',
            'Venus' => 'flytrap'
        ];
    }
}
