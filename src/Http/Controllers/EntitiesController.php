<?php namespace Helium\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntitiesController extends Controller
{

/**
 * Lists entities using a table builder
 *
 * @return void
 */
    public function index()
    {
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
        $entityClass = config('helium.entities.' . $entityType);

        if (!$entityClass) {
            throw new NotFoundHttpException();
        }

        $entity = app()->make($entityClass);
        $form = $entity->getForm()->build(
            $entity->getModelClass()::find($id)
        );

        dd($form);
    }
}
