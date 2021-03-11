<?php

namespace App\Models;

class Billing extends Model
{
    public $string;
    public $updatedString;

    public function __construct()
    {
        $this->string = "MVC + PHP = Awesome!, click here!";
        $this->updatedString = "Updated Data, thanks to MVC and PHP!";
    }
}