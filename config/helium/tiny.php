<?php

return [
    'apikey' => 'no-api-key',
    'profiles' => [
        'common' => [
            'menubar' => false,
            'branding' => false,
            'max_height' => 600,
            'min_height' => 150,
            'autoresize' => true,
            'autoresize_bottom_margin' => 10,
        ],
        'basic' => [
            'plugins' => 'link',
            'toolbar' => 'bold italic link',
            'valid_elements' => '-a[href|target|rel],-strong/b,em/i,br,-p',
            'statusbar' => false,
        ],
        'standard' => [
            'plugins' => 'link lists autoresize',
            'toolbar' => 'bold italic underline strikethrough | styleselect | bullist numlist | link',
            'valid_elements' => '-a[href|target|rel],-strong/b,-em/i,br,-p,-span[!style],' .
                '-li,-ul,-ol,-h1,-h2,-h3,-h4,-h5,-h6,-blockquote',
            'valid_styles' => [
                'span' => 'text-decoration'
            ],
            'style_formats' => [
                ['title' =>  'Heading 1', 'format' => 'h1'],
                ['title' =>  'Heading 2', 'format' => 'h2'],
                ['title' =>  'Heading 3', 'format' => 'h3'],
                ['title' =>  'Heading 4', 'format' => 'h4'],
                ['title' =>  'Heading 5', 'format' => 'h5'],
                ['title' =>  'Heading 6', 'format' => 'h6'],
                ['title' =>  'Paragraph', 'format' => 'p'],
                ['title' =>  'Blockquote', 'format' => 'blockquote'],
            ],
            'statusbar' => false,
        ],
        'full' => [
            'plugins' => 'code lists link autoresize table media',
            'toolbar' => 'code | bold italic underline strikethrough link | styleselect | alignleft aligncenter alignright alignjustify | bullist numlist table media',
        ]
    ],
];
