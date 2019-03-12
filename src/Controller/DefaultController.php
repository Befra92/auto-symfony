<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

//ecrire notre class
class DefaultController{
    //ecrire la méthode index
    public function index(){
        //on dit ce qui va se passer avec la class Response (à importer ds)
        return new Response('Hello World!');
    }
}