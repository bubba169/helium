<?php namespace Helium\Entities\Page;

use Helium\Support\EntityTable;
use Helium\Entities\Page\PageEntity;

class PageTable extends EntityTable
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
