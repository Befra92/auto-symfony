<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitsController extends AbstractController
{
    /**
     * @Route("/produits/add", name="produits_add")
     */
    public function add(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // //Créer une nouvelle instance de notre class Produit
        // $produit = new Produit();
        // $produit->setNom("OnePlus6T");
        // $produit->setMarque("OnePlus");
        // $produit->setPrix(600);
        // $produit->setImage("images/oneplus6t.jpg");
        // $produit->setDescription("Des pirates se serviraient d'iPhone de pré-production pour en trouver les vulnérabilités");
        
        
        $produit = new Produit();

        $form = $this->createFormBuilder($produit)
                    ->add('nom',TextType::class,
                    ['label'=>'Votre nom'])

                    ->add('marque',TextType::class,
                    ['label'=>'Votre marque'])

                    ->add('prix',IntegerType::class,
                    ['label'=>'Votre prix'])

                    ->add('image',TextType::class,
                    ['label'=>'Votre image'])

                    ->add('description',TextType::class,
                    ['label'=>'Votre description'])

                    ->add('Envoyer', SubmitType::class)
                    //méthode getform pour ajouter le formulaire et le retourner à la vue
                    ->getForm();
        //le user à rentrer ses données dans le formulaire
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($produit);
            $em->flush();
        }
        // $em->persist($produit);
        //flush execute en base et persist fait en équivalent le sql
        // $em->flush();
        return $this->render('produits/add.html.twig', [
            //$form = formulaire et form_produit = associé à la val 
            'form_produit'=>$form->createView()
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

        //Appel de la valeur promo + clef promo (ds twig)
        $produitsPromo = $repo->promo(700);
        // dump['produits'];die();
        return $this->render('produits/list.html.twig', 
        ['produits'=>$produits,
        'promos'=>$produitsPromo]);
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
    /**
     * @Route("/produits/{id}/update", name="produit_update")
     */
    public function update($id)
    {
        $em = $this->getDoctrine()->getManager();
        //Créer une nouvelle instance de notre class Produit
        $produit = $em->getRepository(Produit::class)->find($id);

        $produit->setMarque("blackberry");
        //flush on pousse requete dans bdd
        $em->flush($produit);
        return $this->redirectToRoute("produits_list");
    }

      /**
     * @Route("/produits/{id}/delete", name="produit_delete")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        //Créer une nouvelle instance de notre class Produit
        $produit = $em->getRepository(Produit::class)->find($id);

        $em->remove($produit);

        //flush on pousse requete dans bdd
        $em->flush();
        return $this->redirectToRoute("produits_list");
    }
}
