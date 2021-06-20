<?php

namespace Helium\Config\Form\Action;

use Helium\Config\Form\Action\FormAction;
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
