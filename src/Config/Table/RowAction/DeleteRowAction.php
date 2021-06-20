<?php

namespace Helium\Config\Table\RowAction;

use Helium\Config\Table\RowAction\RowAction;
use Helium\Handler\Action\DeleteActionHandler;
use Helium\Handler\Delete\DefaultDeleteHandler;

class DeleteRowAction extends RowAction
{
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'method':
                return 'post';
            case 'route':
                return 'helium.entity.action';
            case 'handlerParams':
                return array_merge(
                    parent::getDefault('handlerParams'),
                    [
                        'deleteHandler' => $this->deleteHandler,
                        'cascade' => $this->cascade,
                        'redirect' => $this->redirect,
                        'redirectParams' => $this->redirectParams,
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
            case 'view':
                return 'helium::partials.button';
            case 'confirm':
                return 'Are you sure you want to delete this entry?';
            case 'redirect':
                return 'helium.entity.list';
            case 'redirectParams':
                return [
                    'type' => $this->entity->slug
                ];
        }

        return parent::getDefault($key);
    }
}
