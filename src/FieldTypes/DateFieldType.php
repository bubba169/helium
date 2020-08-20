<?php namespace Helium\FieldTypes;

use Helium\FieldTypes\FieldType;

class DateFieldType extends FieldType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mergeConfig([
            'flatpickr' => [
                'enableTime' => false,
                'altInput' => true,
                'altFormat' => 'J F Y',
                'dateFormat' => 'Y-m-d',
            ],
            'class' => [
                'flatpickr',
            ],
            'placeholder' => trans('helium::input.date.placeholder'),
            'view' => 'helium::input.date',
        ]);
    }
}
