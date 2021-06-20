<?php

namespace Helium\Traits;

trait HasUrl
{
    /**
     * Builds a url ffrom the config
     */
    public function getUrl(array $routeParams = []): ?string
    {
        if ($this->url) {
            return url($this->url, array_merge($this->routeParams, $routeParams));
        }

        if ($this->route) {
            return route($this->route, array_merge($this->routeParams, $routeParams));
        }

        return null;
    }
}
