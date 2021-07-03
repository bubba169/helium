<?php

namespace Helium\Config\View\Table\RowAction;

use Helium\Config\Action\Action;
use Helium\Handler\Action\DeleteActionHandler;
use Helium\Handler\Delete\DefaultDeleteHandler;

class DeleteRowAction extends Action
{
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'handlerParams':
                return array_merge(
                    parent::getDefault('handlerParams'),
                    [
                        'deleteHandler' => $this->deleteHandler,
                        'cascade' => $this->cascade,
                    ]
                );
            case 'deleteHandler':
                return DefaultDeleteHandler::class;
            case 'cascade':
                return [];
            case 'handler':
                return DeleteActionHandler::class;
            case 'type':
                return 'danger';
            case 'confirm':
                return 'Are you sure you want to delete this entry?';
        }

        return parent::getDefault($key);
    }
}
