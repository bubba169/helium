<?php

namespace Helium\Config\View\Form\Action;

use Helium\Config\Action\Action;
use Helium\Config\Entity;
use Helium\Config\View\Form\FormView;
use Helium\Handler\Action\RequestActionHandler;

class FormAction extends Action
{
    protected FormView $form;

    public function __construct(array $action, FormVIew $form, Entity $entity)
    {
        $this->form = $form;
        parent::__construct($action, $entity);
    }

    public function getDefault(string $key)
    {
        switch ($key) {
            case 'handler':
                return RequestActionHandler::class;
            case 'handlerParams':
                return array_merge(
                    parent::getDefault($key),
                    [
                        'form' => $this->form->slug,
                        'requestType' => $this->requestType,
                    ]
                );
        }

        return parent::getDefault($key);
    }
}
