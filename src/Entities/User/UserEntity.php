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
    protected $tableClass = UserTableBuilder::class;

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
                'page_id' => [
                    'type' => 'multiple',
                    'related' => 'pages',
                    //'relationship' => 'pages',
                    'rules' => [
                        'required'
                    ],
                    'messages' => [
                        'required' => 'Thou must select'
                    ]
                ],
                'pages' => [
                    'name' => 'pages',
                    'type' => 'multiple',
                    'related' => 'pages',
                    'relationship' => 'pages',
                    'rules' => [
                        'required'
                    ],
                    'messages' => [
                        'required' => 'Thou must select'
                    ],
                    'config' => [
                        'name' => 'pages'
                    ]
                ],
            ]
        );
    }
}
