<?php namespace Helium\Entities\User;

use Helium\Support\Entity;
use Helium\Entities\User\UserForm;
use Helium\Entities\User\UserTable;
use Helium\FieldTypes\SelectFieldType;
use Helium\Entities\Page\PageRepository;
use Helium\Entities\User\UserRepository;

class UserEntity extends Entity
{
    /**
     * {@inheritDoc}
     */
    protected $formClass = UserForm::class;

    /**
     * {@inheritDoc}
     */
    protected $tableClass = UserTable::class;

    /**
     * {@inheritDoc}
     */
    protected $repositoryClass = UserRepository::class;

    /**
     * {@inheritDoc}
     */
    public function getFields() : array
    {
        $fields = parent::getFields();

        $fields['page_id'] = array_merge(
            $fields['page_id'],
            [
                'type' => SelectFieldType::class,
                'options' => app()->make(PageRepository::class)->dropdownOptions(),
                'label' => 'Page',
            ]
        );

        return $fields;
    }
}
