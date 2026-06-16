<?php

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        $data['baseUrl'] = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../Views/' . $view . '.php';
    }
}