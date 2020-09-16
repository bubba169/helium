<?php namespace Helium\Form\Field;

use Helium\Form\Field\Field;

class DateField extends Field
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
                'form-control'
            ],
            'placeholder' => trans('helium::input.date.placeholder'),
            'view' => 'helium::input.date',
        ]);
    }
}
