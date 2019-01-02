<?php

namespace I9code\LaravelMetronic472\Http\ViewComposers;

use I9code\LaravelMetronic472\AdminMetronic472;
use Illuminate\View\View;


class AdminMetronic472Composer
{
    /**
     * @var adminMetronic472
     */
    private $adminMetronic472;

    public function __construct(
        AdminMetronic472 $adminMetronic472
    ) {
        $this->adminMetronic472 = $adminMetronic472;
    }

    public function compose(View $view)
    {
        $view->with('adminMetronic472', $this->adminMetronic472);
    }
}
