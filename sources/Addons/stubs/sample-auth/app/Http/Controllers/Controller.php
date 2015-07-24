<?php

namespace {$namespace}\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use \Illuminate\Foundation\Bus\DispatchesCommands;
    use \Illuminate\Foundation\Validation\ValidatesRequests;

    public function __construct()
    {
        View::share('addon_name', addon_name());
    }
}
