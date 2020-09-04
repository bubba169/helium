<?php

use Helium\Table\Column\BooleanColumn;

return [
    /**
     * Add any eitites here so they can be managed in the CMS
     */
    'entities' => [
        'users' => \Helium\Entities\User\UserEntity::class,
        'pages' => \Helium\Entities\Page\PageEntity::class,
        'files' => \Helium\Entities\File\FileEntity::class,
    ],

    'field_types' => [
        /**
         * Maps a DBAL column types and more to field types in Helium.
         * Any not specified here will default to TextFieldType.
         */
        'boolean' => \Helium\Form\Field\BooleanField::class,
        'date' => \Helium\Form\Field\DateField::class,
        'date_immutable' => \Helium\Form\Field\DateField::class,
        'datetime' => \Helium\Form\Field\DateTimeField::class,
        'datetime_immutable' => \Helium\Form\Field\DateTimeField::class,
        'text' => \Helium\Form\Field\HtmlField::class,
        'textarea' => \Helium\Form\Field\TextAreaField::class,
        'select' => \Helium\Form\Field\SelectField::class,
        'multiple' => \Helium\Form\Field\MultipleField::class,
        'image' => \Helium\Form\Field\ImageField::class,
    ],

    'column_types' => [
        'boolean' => \Helium\Table\Column\BooleanColumn::class,
    ],
];
