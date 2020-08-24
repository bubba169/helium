<?php namespace Helium\Http\Controllers;

use Illuminate\Http\Request;
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
    public function edit(Request $request, string $entityType, int $id)
    {
        $entity = $this->dispatchNow(new GetEntity($entityType));

        $form = $entity->getFormBuilder()
            ->setInstance($entity->getRepository()->find($id))
            ->getForm();

        if ($request->isMethod('post')) {
            $entity->getFormHandler()
                ->validate($request->all())
                ->post($request->all());

            return back()->with('message', [
                'type' => 'success',
                'message' => 'Saved successfully'
            ]);
        }

        return view('helium::form', [
            'form' => $form
        ]);
    }
}
