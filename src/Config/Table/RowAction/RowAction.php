<?php

namespace Helium\Config\Table\RowAction;

use Helium\Config\Action;
use Helium\Config\Entity;
use Helium\Config\Table\Table;

class RowAction extends Action
{
    protected Table $table;

    public function __construct(array $action, Table $table, Entity $entity)
    {
        $this->table = $table;
        parent::__construct($action, $entity);
    }

    public function getDefault(string $key)
    {
        switch ($key) {
            case 'view':
                return 'helium::partials.link';
            case 'route':
                return 'helium.entity.form';
            case 'routeParams':
                return [
                    'form' => $this->action,
                    'type' => $this->entity->slug,
                ];
            case 'handlerParams':
                return array_merge(
                    parent::getDefault($key),
                    [
                        'table' => $this->table->slug,
                    ]
                );
        }

        return parent::getDefault($key);
    }
}
