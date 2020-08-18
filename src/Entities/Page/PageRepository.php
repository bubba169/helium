<?php namespace Helium\Entities\Page;

use Helium\Entities\Page\Page;
use Helium\Support\EntityRepository;

class PageRepository extends EntityRepository
{
    /**
     * Constructor
     *
     * @param UserModel $model
     */
    public function __construct(PageEntity $entity, Page $model)
    {
        parent::__construct($entity, $model);
    }
}
