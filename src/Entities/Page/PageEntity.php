<?php namespace Helium\Entities\Page;

use Helium\Support\Entity;
use Helium\Entities\Page\PageForm;
use Helium\Entities\Page\PageTable;
use Helium\Entities\Page\PageRepository;

class PageEntity extends Entity
{
     /**
     * {@inheritDoc}
     */
    protected $formClass = PageForm::class;

    /**
     * {@inheritDoc}
     */
    protected $tableClass = PageTable::class;

    /**
     * {@inheritDoc}
     */
    protected $repositoryClass = PageRepository::class;
}
