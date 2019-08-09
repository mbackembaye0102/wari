<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Entity\Compte;

use App\Form\DepotType;
use App\Form\CompteType;
use App\Entity\Partenaire;
use App\Entity\Utilisateur;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api")
 */
class TransactionController extends AbstractController
{
    /**
     * @Route("/depots", name="add_depot", methods={"POST"})
      *@IsGranted("ROLE_CAISSIER")

     */
    
public function new(Request $request,EntityManagerInterface $entityManager ): Response
    {
        $depot = new Depot();
        $form = $this->createForm(DepotType::class,$depot);
        $data=$request->request->all();
        $depot->setDateDepot(new \Datetime());
        $depot->getMontant();
       
        $form->submit($data);
        if($form->isSubmitted()){  
             $depot->getMontant();
                if ($depot->getMontant()>=75000) {
                $compte= $depot->getCompte();
                    
                    $user=$this->getUser();
                    $depot->setUtilisateur($user);

                $compte->setSolde($compte->getSolde()+$depot->getMontant());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($compte);
                $entityManager->persist($depot);
                $entityManager->flush();
            return new Response('Le dépôt a été effectué',Response::HTTP_CREATED);
            }
            return new Response('Le montant du depot doit etre superieur ou egal a 75 000',Response::HTTP_CREATED);
         
        }

      $data = [
            'status' => 500,
            'message' => 'Vous devez renseigner le montant et le compte où doit être effectuer le dépot '
        ];
        return new Response($data, 500); 

    }
    
    
     /**
     * @Route("/comptes", name="add_compte", methods={"POST"}) 
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function addCompte(Request $request,EntityManagerInterface $entityManager){

        $compte = new Compte();
        $form = $this->createForm(CompteType::class, $compte);
        $values =$request->request->all();

        $form->handleRequest($request);
        $form->submit($values);
        if ($form->isSubmitted() && $form->isValid()) {

            $a="SA";
            $b=rand(1000000000000,9999999999999);
            $numerocompte=$a.$b;
            $compte->setNumeroCompte($numerocompte);
            $compte->setSolde(1);
              // recuperer id du partenaire
            $repos=$this->getDoctrine()->getRepository(Partenaire::class);
            $partenaire=$repos->findAll();
            $compte->setPartenaire($partenaire[0]);

           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($compte);
           $entityManager->flush();
           
            $data = [
               'status1' => 201,
               'message1' => 'Le compte a été créé'
           ];
           return new JsonResponse($data, 201);
        }
        $data = [
            'status1' => 500,
            'message1' => 'Vous devez renseigner les champs numero compte et solde'
        ];
        return new JsonResponse($data, 500);
    }
   
}
