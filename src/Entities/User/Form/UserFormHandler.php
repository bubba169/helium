<?php namespace Helium\Entities\User\Form;

use Helium\Form\FormHandler;
use Helium\Entities\User\UserEntity;
use Illuminate\Support\Facades\Hash;

class UserFormHandler extends FormHandler
{
    protected $rules = [
        'email' => [
            'required'
        ]
    ];

    /**
     * Constructor
     *
     * @param UserEntity $entity
     */
    public function __construct(UserEntity $entity)
    {
        parent::__construct($entity);
    }

    /**
     * {@inheritDoc}
     *
     * Unset the password if not set.
     */
    public function post(array $postData) : self
    {
        if (empty($postData['password'])) {
            unset($postData['password']);
        } else {
            $postData['password'] = Hash::make($postData['password']);
        }

        return parent::post($postData);
    }
}
