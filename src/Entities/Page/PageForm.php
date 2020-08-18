<?php namespace Helium\Entities\Page;

use Helium\Support\EntityForm;
use Helium\Entities\Page\PageEntity;

class PageForm extends EntityForm
{
    /**
     * Construct
     *
     * @param PageEntity $entity
     */
    public function __construct(PageEntity $entity)
    {
        parent::__construct($entity);
    }
}
