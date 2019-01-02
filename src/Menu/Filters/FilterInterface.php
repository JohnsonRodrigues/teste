<?php

namespace I9code\LaravelMetronic472\Menu\Filters;



use I9code\LaravelMetronic472\Menu\Builder;

interface FilterInterface
{
    public function transform($item, Builder $builder);
}
