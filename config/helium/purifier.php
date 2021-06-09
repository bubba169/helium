<?php

return [
    // Use the default config with added iframes for youtube and vimeo videos
    'config' => [
        'HTML.SafeIframe' => true,
        'URI.SafeIframeRegexp' => '%^https://((www\.)?youtube\.com|youtu\.be|player\.vimeo\.com)/%',
        'HTML.ForbiddenAttributes' => [
            'iframe@style'
        ],
    ],
    'custom' => [
        'attributes' => [
            'iframe' => [
                // Don't strip allowfullscreen attributes
                'allowfullscreen' => 'Bool'
            ]
        ]
    ],
];
