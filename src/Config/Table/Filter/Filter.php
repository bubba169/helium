<?php

namespace Helium\Config\Table\Filter;

use Helium\Config\Entity;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Helium\Config\Form\Field\Input;
use Helium\Config\Form\Field\BaseField;

class Filter extends Input
{
    public string $handler;

    /**
     * Search field config
     */
    public function __construct(array $filter, Entity $config)
    {
        parent::__construct($filter, $config);
        $this->handler = Arr::get('handler', $filter, $this->defaultHandler());
    }

    /**
     * {@inheritDoc}
     */
    protected function defaultHandler(): string
    {
        return DefaultFilterHandler::class;
    }

    protected function defaultPlaceholder(): ?string
    {
        return 'Filter By ' . Str::title(str_humanise($this->slug));
    }
}
