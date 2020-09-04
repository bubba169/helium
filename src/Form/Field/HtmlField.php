<?php namespace Helium\Form\Field;

use Helium\Form\Field\Field;

class HtmlField extends Field
{
    protected $profiles = [
        'basic' => [
            'branding' => false,
            'menubar' => false,
            'plugins' => 'link code',
            'toolbar' => 'undo redo | bold italic underline link | code',
        ],
        'default' => [
            'branding' => false,
            'menubar' => false,
            'plugins' => 'lists link code',
            'toolbar' => 'undo redo | styleselect | bold italic underline link | bullist numlist | alignleft aligncenter alignright | code',
            'style_formats' => [
                [
                    'title' => 'Headings',
                    'items' => [
                        ['title' => 'Heading 2', 'format' => 'h2'],
                        ['title' => 'Heading 3', 'format' => 'h3'],
                        ['title' => 'Heading 4', 'format' => 'h4'],
                        ['title' => 'Heading 5', 'format' => 'h5'],
                    ],
                ],
                [
                    'title' => 'Blocks',
                    'items' => [
                        ['title' => 'Paragraph', 'format' => 'p'],
                        ['title' => 'Blockquote', 'format' => 'blockquote'],
                    ],
                ],
            ]
        ]
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mergeConfig([
            'view' => 'helium::input.html',
            'class' => [
                'wysiwyg'
            ],
            'tinymce' => 'default'
        ]);
    }

    /**
     * Gets the current config
     *
     * @return string
     */
    public function getTinyConfig()
    {
        $config = $this->getConfig('tinymce');
        if (is_string($config) && array_key_exists($config, $this->profiles)) {
            return json_encode($this->profiles[$config]);
        } elseif (is_array($config)) {
            return json_encode($config);
        }

        return $config;
    }
}
