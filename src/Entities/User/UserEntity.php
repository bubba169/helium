<?php namespace Helium\Entities\User;

use Helium\Support\Entity;
use Helium\Entities\User\Form\UserFormHandler;
use Helium\Entities\Page\PageRepository;
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
                'name' => [
                    'config' => [
                        'attributes' => [
                            'required' => true
                        ]
                    ]
                ],
                'pages' => [
                    'name' => 'pages',
                    'type' => 'multiple',
                    'options' => 'pages',
                    'relationship' => 'pages',
                    'config' => [
                        'name' => 'pages'
                    ]
                ],
                'avatar_file_id' => [
                    'type' => 'image'
                ]
            ]
        );
    }
}
