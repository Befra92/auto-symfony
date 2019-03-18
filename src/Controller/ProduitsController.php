<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitsController extends AbstractController
{
    /**
     * @Route("/produit/add", name="produit_add")
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
                    ->add('image')
                    ->add('description')
                    ->add('Envoyer', SubmitType::class)
                    ->getForm();
//Fin de la creation
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute("produit_list");
        }
       /* $produit->setNom("Samsung Galaxy S10");
        $produit->setMarque("Samsung");
        $produit->setPrix(1009);
        $produit->setImage("https://via.placeholder.com/150/09f.png/#BA3B24  

        C/O https://placeholder.com/");
        $produit->setDescription("Non, vous ne rêvez pas : le Find X est un smartphone sans encoche et quasiment sans bords. Son système innovant de capteurs photo coulissants et son design futuriste en font le fer de lance de la marque Oppo. Présentation de celui qui a tout pour devenir une référence dans le haut de gamme. ");*/

        //$em->persist($produit);
        //$em->flush();
        return $this->render('produit/add.html.twig', [
            'form_produit'=>$form->createView()
        ]);
    }
    /**
     * @Route("/produit/list", name="produit_list")
     */
    public function list(){

        $repo = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $repo->findAll();
        
        $produitsPromos = $repo->promo(1000);

        return $this->render('produits/list.html.twig',
        ['produits'=>$produits, 'promos'=>$produitsPromos]);
    }

    /**
     * @Route("/produit/{id}/show", name="produit_show")
     */
    public function show(Produit $produit,  ObjectManager $manager){

        //$repo = $this->getDoctrine()->getRepository(Produit::class);
       //$produit =  $repo->find($id);

       return $this->render('produit/show.html.twig', ['produit'=>$produit]);
    }

    /**
     * @Route("/produit/{id}/delete", name="produit_delete")
     */
    public function delete($id){

        $em =  $this->getDoctrine()->getManager();

        $produit = $em->getRepository(Produit::class)
                        ->find($id) ;

        $em->remove($produit);

        $em->flush();

        return $this->redirectToRoute("produit_list");
    }

    /**
     * @Route("/produit/{id}/update", name="produit_update")
     */
    public function update($id){
        $em =  $this->getDoctrine()->getManager();

        $produit = $em->getRepository(Produit::class)
                        ->find($id) ;

        $produit->setMarque("blackberry");
        
        $em->flush($produit);

        return $this->redirectToRoute("produit_list");
    }

    /**
     * @Route("/produit/{id}/editUpdate", name="produit_editUpdate")
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

            return $this->redirectToRoute("produit_list");
        }
        return $this->render('produit/editUpdate.html.twig',
               ['form_editUpdate'=>$form->createView()]
        );
    }
}
