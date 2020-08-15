<?php

return [
    /**
     * Add any eitites here so they can be managed in the CMS
     */
    'entities' => [
        'users' => \Helium\Entities\User\User::class,
    ],

    'database' => [
        /**
         * Maps a DBAL column types to field types in Helium.
         * Any not specified here will default to StringFieldType.
         */
        'type_map' => [
            'boolean' => \Helium\FieldTypes\BooleanFieldType::class,
            'date' => \Helium\FieldTypes\DateFieldType::class,
            'date_immutable' => \Helium\FieldTypes\DateFieldType::class,
            'datetime' => \Helium\FieldTypes\DateTimeFieldType::class,
            'datetime_immutable' => \Helium\FieldTypes\DateTimeFieldType::class,
            'text' => \Helium\FieldTypes\HtmlFieldType::class,
        ]
    ]
];
