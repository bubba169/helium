<?php namespace Helium\Entities\User;

use Helium\Traits\HeliumEntity;
use Helium\Contract\HeliumEntity as HeliumEntityContract;
use Helium\Entities\User\Form\UserFormBuilder;
use Helium\Entities\User\Form\UserFormHandler;
use Helium\Entities\User\Table\UserTableBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HeliumEntityContract
{
    use HeliumEntity {
        getFields as entityGetFields;
    }

    /**
     * {@inheritDoc}
     */
    public function getFields() : array
    {
        $fields = $this->entityGetFields();

        return array_merge_deep(
            $fields,
            [
                'email' => [
                    'required' => true
                ],
                'pages' => [
                    'name' => 'pages',
                    'type' => 'multiple',
                    'relationship' => 'pages',
                    'required' => true
                ],
                'avatar_file_id' => [
                    'type' => 'select',
                    'relationship' => 'pages',
                    'options' => 'pages',
                    'required' => true
                ],
                'profile' => [
                    'required' => true,
                ]
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function getFormBuilderClass(): string
    {
        return UserFormBuilder::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getFormHandlerClass(): string
    {
        return UserFormHandler::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableBuilderClass(): string
    {
        return UserTableBuilder::class;
    }
}
