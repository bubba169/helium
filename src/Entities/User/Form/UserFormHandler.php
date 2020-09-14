<?php namespace Helium\Entities\User\Form;

use Illuminate\Support\Arr;
use Helium\Form\FormHandler;
use Illuminate\Support\Facades\Hash;

class UserFormHandler extends FormHandler
{
    /**
     * {@inheritDoc}
     *
     * Password field is not required
     */
    public function buildRules(array $fields) : array
    {
        $rules = parent::buildRules($fields);
        $rules['password'] = array_filter($rules['password'], function ($value) {
            $value != 'required';
        });

        return $rules;
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
