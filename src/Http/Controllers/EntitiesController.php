<?php namespace Helium\Http\Controllers;

use Helium\Commands\GetEntity;
use App\Http\Controllers\Controller;

class EntitiesController extends Controller
{

/**
 * Lists entities using a table builder
 *
 * @return void
 */
    public function index(string $entityType)
    {
        $entity = $this->dispatchNow(new GetEntity($entityType));
        dd('index');
    }

/**
 * Edit an entity using a form builder
 *
 * @param string $entity The entity type
 * @param int $id The entity id
 * @return void
 */
    public function edit(string $entityType, int $id)
    {
        $entity = $this->dispatchNow(new GetEntity($entityType));

        $form = $entity->getForm()->build(
            $entity->getRepository()->find($id)
        );

        dd($form);
    }

}
