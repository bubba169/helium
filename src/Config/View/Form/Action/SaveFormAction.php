<?php

namespace Helium\Config\View\Form\Action;

use Helium\Config\View\Form\Action\FormAction;
use Helium\Http\Requests\SaveEntityFormRequest;

class SaveFormAction extends FormAction
{
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'requestType':
                return SaveEntityFormRequest::class;
        }

        return parent::getDefault($key);
    }
}
