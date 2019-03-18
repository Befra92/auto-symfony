<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BackProduitController extends AbstractController
{
    /**
     * @Route("/back/produit", name="back_produit")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $repo->findAll();
        
        $produitsPromos = $repo->promo(1000);

        return $this->render('back_produit/index.html.twig', 
        ['tabProduits'=>$produits]);
    }
     /**
     * @Route("/produit/back/add", name="back_produit_add")
     */
    public function add(Request $request)
    {
        $em =  $this->getDoctrine()->getManager();

        $produit = new Produit();
 // creation du formulaire
        $form = $this->createFormBuilder($produit)
                    ->add('nom',TextType::class,['label'=>'Votre nom'])
                    ->add('marque')
                    ->add('prix', IntegerType::class)
                    ->add('image', FileType::class)
                    ->add('description')
                    ->add('Envoyer', SubmitType::class)
                    ->getForm();
//Fin de la creation
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('image')->getData();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();          
            $file->move($this->getParameter('uploads'), $fileName);

            //setImage fait le nom ds la bdd
            $produit->setImage($fileName);

            $em->persist($produit);         
            $em->flush();
            $this->addFlash('success','Produit ajouté avec succès');
            return $this->redirectToRoute("back_produit");
        }
      
        return $this->render('back_produit/add.html.twig', [
            'form_produit'=>$form->createView()
        ]);
    }
    public function generateUniqueFileName(){
        return md5(uniqid());
    }
     /**
     * @Route("/produit/back/{id}/editUpdate", name="back_produit_editUpdate")
     */
    public function editUpdate($id, Request $request){

        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->find($id);

        $form = $this->createFormBuilder($produit)
                    ->add('nom')
                    ->add('marque')
                    ->add('prix')
                    ->add('image')
                    ->add('description')
                    ->add('Modifier', SubmitType::class)
                    ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute("back_produit");
        }
        return $this->render('back_produit/editUpdate.html.twig',
               ['form_editUpdate'=>$form->createView()]
        );
    }
    /**
     * @Route("/produit/back/{id}/delete", name="back_produit_delete")
     */
    public function delete($id){

        $em =  $this->getDoctrine()->getManager();

        $produit = $em->getRepository(Produit::class)
                        ->find($id) ;

        $em->remove($produit);

        $em->flush();
        $this->addFlash('suppression', 'Produit supprimé avec succès');

        return $this->redirectToRoute("back_produit");
    }
}
