<?php

namespace App\Core;

class Model
{
    protected ?Database $db = null;

    public function __construct()
    {
        $this->db = new Database();
    }
}
