<?php namespace Helium\FieldTypes;

use Helium\FieldTypes\FieldType;

class DateTimeFieldType extends FieldType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mergeConfig([
            'flatpickr' => [
                'enableTime' => true,
                'enableSeconds' => true,
                'time_24hr' => true,
                'altInput' => true,
                'altFormat' => 'J F Y H:i:S',
                'dateFormat' => 'Y-m-d H:i:S',
            ],
            'class' => [
                'flatpickr',
            ],
            'placeholder' => trans('helium::input.datetime.placeholder'),
            'view' => 'helium::input.date',
        ]);
    }
}
