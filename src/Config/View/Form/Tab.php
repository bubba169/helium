<?php

namespace Helium\Config\View\Form;

use Helium\Config\Entity;
use Illuminate\Support\Str;
use Helium\Traits\HasConfig;

class Tab
{
    use HasConfig;

    protected Entity $entity;

    public function __construct(array $tab, Entity $entity)
    {
        $this->entity = $entity;
        $this->mergeConfig($tab);
    }

    public function getDefault(string $key)
    {
        switch ($key) {
            case 'label':
                return Str::title(str_humanise($this->slug));
        }

        return null;
    }
}
