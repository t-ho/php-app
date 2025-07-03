<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminBaseController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->setDefaultLayout('layouts/admin');
        $this->setDefaultTitleSuffix(config('app.name') . ' Admin Dashboard');
    }
}
