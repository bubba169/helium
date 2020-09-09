<?php namespace Helium\Entities\User;

use Helium\Support\Entity;
use Helium\Entities\User\Form\UserFormHandler;
use Helium\Entities\User\Form\UserFormBuilder;
use Helium\Entities\User\Table\UserTableBuilder;
use Helium\Entities\User\UserRepository;

class UserEntity extends Entity
{
    /**
     * {@inheritDoc}
     */
    protected $formBuilderClass = UserFormBuilder::class;

    /**
     * {@inheritDoc}
     */
    protected $tableBuilderClass = UserTableBuilder::class;

    /**
     * {@inheritDoc}
     */
    protected $repositoryClass = UserRepository::class;

    /**
     * {@inheritDoc}
     */
    protected $formHandlerClass = UserFormHandler::class;

    /**
     * {@inheritDoc}
     */
    public function getFields() : array
    {
        $fields = parent::getFields();

        return array_merge_deep(
            $fields,
            [
                'pages' => [
                    'name' => 'pages',
                    'type' => 'multiple',
                    'relationship' => 'pages',
                ],
                'avatar_file_id' => [
                    'type' => 'image'
                ]
            ]
        );
    }
}
