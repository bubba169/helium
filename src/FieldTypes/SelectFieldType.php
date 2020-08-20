<?php namespace Helium\FieldTypes;

use Illuminate\Support\Collection;

class SelectFieldType extends FieldType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mergeConfig([
            'view' => 'helium::input.select',
            'placeholder' => trans('helium::input.select.placeholder'),
        ]);
    }

    /**
     * Gets the current options
     *
     * @return array
     */
    public function getOptions() : array
    {
        return $this->getConfig('options');
    }
}
