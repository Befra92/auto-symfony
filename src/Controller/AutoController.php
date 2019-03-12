<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AutoController extends AbstractController
{
    /**
     * @Route("/auto", name="auto")
     */
    public function index()
    {
        return $this->render('auto/index.html.twig', [
            'controller_name' => 'AutoController',
        ]);
    }

     /**
     * @Route("exemples", name="exemples")
     */

    //Créer le fichier de rendu "exemple" (1er para l'url et 2e para une vue [])
    public function exemples()
    {
        $titre = "Ma Première page";
        $admin = true;
        $voitures = ["Renault","Peugeot","Citroen","Fiat"];
        $age = 18;
        return $this->render('auto/exemples.html.twig',
        ['titre'=>$titre, 'test'=>$age, 
        'voitures'=>$voitures]);
    }
    /**
     * *@Route("/donnees", name="donnees")
     */
    public function donnees(){
        $voitures = [
        ['id'=>1,'marque'=>'Renault', 'modele'=>'Clio2', 'pays'=>'France',"images"=>"images/mercedes.png"],
        ['id'=>2,'marque'=>'Renault', 'modele'=>'Clio2', 'pays'=>'France',"images"=>"images/peugeot505.png"],
        ['id'=>3,'marque'=>'Renault', 'modele'=>'Clio2', 'pays'=>'France',"images"=>"images/porshe.png"],
        ['id'=>4,'marque'=>'Renault', 'modele'=>'Clio2', 'pays'=>'France',"images"=>"images/clio2.jpg"]
        ];

        return $this->render('auto/donnees.html.twig',
        ["voitures"=>$voitures]);
    }
}