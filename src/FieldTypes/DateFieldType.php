<?php namespace Helium\FieldTypes;

use Helium\FieldTypes\FieldType;

class DateFieldType extends FieldType
{
    protected $view = 'helium::input.date';

    public function __construct()
    {
        parent::__construct();

        $this->mergeConfig([
            'flatpickr' => [
                'enableTime' => false,
                'altInput' => true,
                'altFormat' => 'J F Y',
                'dateFormat' => 'Y-m-d'
            ]
        ]);
        $this->addClass('flatpickr');
        $this->setPlaceholder(trans('helium::input.date.placeholder'));
    }
}
