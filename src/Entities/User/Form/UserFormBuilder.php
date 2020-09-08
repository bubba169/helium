<?php namespace Helium\Entities\User\Form;

use Helium\Form\FormBuilder;
use Helium\Entities\User\UserEntity;

class UserFormBuilder extends FormBuilder
{
    /**
     * Form sections
     *
     * @var array
     */
    /*protected $sections = [
        'user' => [
            'label' => 'User',
            'fields' => [
                'id',
                'name',
                'email',
                'profile',
                'avatar_file_id',
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
    ];*/

    /**
     * Construct
     *
     * @param UserEntity $entity
     */
    public function __construct(UserEntity $entity)
    {
        parent::__construct($entity);

        $this->skip[] = 'remember_token';
    }
}
