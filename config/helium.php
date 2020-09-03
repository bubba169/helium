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
        'boolean' => \Helium\FieldTypes\BooleanFieldType::class,
        'date' => \Helium\FieldTypes\DateFieldType::class,
        'date_immutable' => \Helium\FieldTypes\DateFieldType::class,
        'datetime' => \Helium\FieldTypes\DateTimeFieldType::class,
        'datetime_immutable' => \Helium\FieldTypes\DateTimeFieldType::class,
        'text' => \Helium\FieldTypes\HtmlFieldType::class,
        'textarea' => \Helium\FieldTypes\TextAreaFieldType::class,
        'select' => \Helium\FieldTypes\SelectFieldType::class,
        'multiple' => \Helium\FieldTypes\MultipleFieldType::class,
        'image' => \Helium\FieldTypes\ImageFieldType::class,
    ],

    'column_types' => [
        'boolean' => \Helium\Table\Column\BooleanColumn::class,
    ],
];
