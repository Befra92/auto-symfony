<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitsController extends AbstractController
{
    /**
     * @Route("/produits/add", name="produits_add")
     */
    public function add()
    {
        $em = $this->getDoctrine()->getManager();
        //Créer une nouvelle instance de notre class Produit
        $produit = new Produit();
        $produit->setNom("OnePlus6T");
        $produit->setMarque("OnePlus");
        $produit->setPrix(600);
        $produit->setImage("images/oneplus6t.jpg");
        $produit->setDescription("Des pirates se serviraient d'iPhone de pré-production pour en trouver les vulnérabilités");
        
        $em->persist($produit);
        //flush execute en base et persist fait en équivalent le sql
        $em->flush();
        return $this->render('produits/add.html.twig', [
        ]);
    }
    /**
     * @Route("/produits/list", name="produits_list")
     */
    public function list()
    {
        //méthode qui permet de recuperer les produits de la bdd
        $repo = $this->getDoctrine()->getRepository(Produit::class);
        //$list=tab de données qui retourne les produits à la vue list.html.twig
        $produits = $repo->findAll();
        // dump['produits'];die();
        return $this->render('produits/list.html.twig', ['produits'=>$produits]);
    }
     /**
     * @Route("/produits/{id}/show/", name="produit_show")
     */
    public function show($id){
    // aller récupérer le doctrine avec $repo, on veut récupérer 1 article
    $repo = $this->getDoctrine()->getRepository(Produit::class);
    $produits = $repo->find($id);
    return $this->render('produits/show.html.twig', ['produits'=>$produits]);
    }
}
