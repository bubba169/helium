<?php namespace Helium\Entities\User\Form;

use Helium\Form\FormBuilder;

class UserFormBuilder extends FormBuilder
{
    /**
     * Form fields
     *
     * @var array
     */
    protected $fields = [
        'pages' => [
            'options' => 'pages',
        ],
        'password' => [
            'required' => false
        ]
    ];

    /**
     * Form sections
     *
     * @var array
     */
    protected $sections = [
        'user' => [
            'label' => 'User',
            'fields' => [
                'id',
                'name',
                'email',
                'profile',
                'avatar_file_id',
                'is_active',
            ]
        ],
        'pages' => [
            'label' => 'Pages',
            'fields' => [
                'pages',
            ]
        ],
        'password' => [
            'label' => 'Password',
            'fields' => [
                'password',
            ]
        ]
    ];
}
