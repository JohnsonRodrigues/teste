<?php

namespace I9code\LaravelMetronic472\Events;


use I9code\LaravelMetronic472\Menu\Builder;

class BuildingMenu
{
    public $menu;

    public function __construct(Builder $menu)
    {
        $this->menu = $menu;
    }
}
