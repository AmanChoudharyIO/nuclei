<?php

namespace App\Views;

use App\Controllers\BillingController;

class SampleView extends View
{
    private $controller;

    public function __construct()
    {
        $this->controller = new BillingController();
    }

    public function output($action = '')
    {
        return "<p><a href=\"index.php?action=clicked\">" . $this->controller->getString($action) . "</a></p>";
    }
}