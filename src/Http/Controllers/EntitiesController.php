<?php namespace Helium\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Helium\Contract\HeliumEntity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntitiesController extends Controller
{
    /**
     * Lists entities using a table builder
     *
     * @param string $entityType
     * @return View
     */
    public function index(string $entityType)
    {
        $entity = $this->getEntity($entityType);

        $table = $entity->getTableBuilder()
            ->getTable();

        return view($table->getView(), [
            'table' => $table,
        ]);
    }

    /**
     * Edit an entity using a form builder
     *
     * @param string $entity The entity type
     * @param int $id The entity id
     * @return void
     */
    public function edit(Request $request, string $entityType, int $id)
    {
        $entity = $this->getEntity($entityType);

        $form = $entity->getFormBuilder()
            ->setInstance($entity->getRepository()->find($id))
            ->getForm();

        return view('helium::form', [
            'form' => $form
        ]);
    }

    /**
     * Shows a form to create a new entity
     *
     * @return View
     */
    public function create(Request $request, string $entityType)
    {
        $entity = $this->getEntity($entityType);

        $form = $entity->getFormBuilder()->getForm();

        return view('helium::form', [
            'form' => $form
        ]);
    }

    /**
     * Validates and saves the post data
     *
     * @return Redirect
     */
    public function save(Request $request, string $entityType)
    {
        $entity = $this->getEntity($entityType);

        $entity->getFormHandler()
            ->validate($request->all())
            ->post($request->all());

        return back()->with('message', [
            'type' => 'success',
            'message' => 'Saved successfully'
        ]);
    }

    /**
     * Gets an Entity by its slug
     *
     * @param string $slug
     * @return EntityInterface
     */
    protected function getEntity(string $slug) : HeliumEntity
    {
        $entityClass = config('helium.entities.' . $slug);

        if (!$entityClass || !class_exists($entityClass)) {
            throw new NotFoundHttpException();
        }

        return app()->make($entityClass);
    }
}
