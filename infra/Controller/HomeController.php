<?php

namespace App\Controller;

class HomeController extends AbstractController
{
    public function index()
    {
        return $this->render('pages/home.html.twig');
    }
}
