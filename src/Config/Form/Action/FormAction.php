<?php

namespace Helium\Config\Form\Action;

use Helium\Config\Action;
use Helium\Config\Entity;
use Helium\Config\Form\Form;
use Helium\Handler\Action\RequestActionHandler;

class FormAction extends Action
{
    protected Form $form;

    public function __construct(array $action, Form $form, Entity $entity)
    {
        $this->form = $form;
        parent::__construct($action, $entity);
    }

    public function getDefault(string $key)
    {
        switch ($key) {
            case 'view':
                return 'helium::partials.button';
            case 'method':
                return 'post';
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
