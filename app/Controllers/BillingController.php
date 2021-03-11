<?php

namespace App\Controllers;

use App\Models\Billing;

class BillingController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new Billing();
    }

    public function getString($action = '')
    {
        if ($action != '') {
            return $this->model->updatedString;
        }
        return $this->model->string;
    }
}