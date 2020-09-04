<?php namespace Helium\Table\Column;

use Helium\Traits\HasConfig;
use Illuminate\Database\Eloquent\Model;

class Column
{
    use HasConfig;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mergeConfig([
            'view' => 'helium::column.value',
        ]);
    }

    /**
     * Gets the column name
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->getConfig('name');
    }

    /**
     * Gets the label
     *
     * @return string
     */
    public function getLabel() : string
    {
        return $this->getConfig(
            'label',
            ucwords(str_replace('_', ' ', $this->getName()))
        );
    }

    /**
     * Renders the value
     *
     * @param Model $row
     * @return string
     */
    public function getValue(Model $row) : string
    {
        return e($row->{$this->getName()});
    }

    /**
     * Gets the view to render a cell
     *
     * @return string
     */
    public function getView() : string
    {
        return $this->getConfig('view');
    }
}
