<?php

namespace Helium\Support;

use Illuminate\Support\Arr;

class IconHelper
{
    protected const ICON_MAP = [
        'add' => 'fas fa-plus',
        'create' => 'fas fa-plus',
        'delete' => 'fas fa-trash-alt',
        'edit' => 'fas fa-edit',
        'new' => 'fas fa-plus',
        'save' => 'fas fa-save',
        'view' => 'fas fa-eye',
    ];

    public static function iconFor(string $action): ?string
    {
        return Arr::get(self::ICON_MAP, $action, null);
    }
}
