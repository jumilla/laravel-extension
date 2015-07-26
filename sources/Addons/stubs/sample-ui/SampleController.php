<?php

namespace {$namespace}\Http\Controllers;

class SampleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return addon_view(addon_name(), 'index');
    }
}
