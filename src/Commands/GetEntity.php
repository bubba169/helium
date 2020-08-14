<?php namespace Helium\Commands;

use Helium\Support\Entity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetEntity
{

    /**
     * Constructor
     *
     * @param string $slug The entity slug
     */
    public function __construct(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * Gets the entity using the slug
     *
     * @return Entity
     */
    public function handle() : Entity
    {
        $entityClass = config('helium.entities.' . $this->slug);

        if (!$entityClass || !class_exists($entityClass)) {
            throw new NotFoundHttpException();
        }

        return app()->make($entityClass);
    }

}
