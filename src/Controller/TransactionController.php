<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Entity\Tarif;
use App\Entity\Compte;
use App\Form\DepotType;
use App\Form\CompteType;
use App\Form\RetraitType;
use App\Entity\Expediteur;
use App\Entity\Partenaire;
use App\Entity\Transaction;
use App\Entity\Utilisateur;
use App\Entity\Beneficiaire;
use App\Form\ExpediteurType;
use App\Form\UserCompteType;
use App\Form\TransactionType;
use App\Form\UtilisateurType;
use App\Form\BeneficiaireType;
use App\Repository\PartenaireRepository;
use App\Repository\CompteRepository;
use App\Repository\DepotRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use App\Repository\BeneficiaireRepository;
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
      *@IsGranted({"ROLE_CAISSIER"})
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
     *@IsGranted({"ROLE_SUPER_ADMIN"})
     */
    public function addCompte(Request $request,EntityManagerInterface $entityManager){

        $compte = new Compte();
        $form = $this->createForm(CompteType::class, $compte);
        $values =$request->request->all();
        $form->handleRequest($request);
        $form->submit($values);
        if ($form->isSubmitted() ) {
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

        
     /**
     * @Route("/envoie", name="envoie", methods={"POST"}) 
     *@IsGranted({"ROLE_ADMIN_PARTENAIRE", "ROLE_USERS"})
     */
    public function envoie (Request $request,EntityManagerInterface $entityManager){
        // AJOUT OPERATION
        $transaction= new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);
        $values=$request->request->all();
        $form->submit($values);

       if ($form->isSubmitted()) {
        $transaction->setDateEnvoie(new \DateTime());
        //generation du code
        $e="W";
        $c=rand(10000000,99999999);
        $codes=$e.$c;
        $transaction->setCode($codes);

        // recuperer l'id du guichetier
        $user=$this->getUser();
        $transaction->setGuichetier($user);


           // recuperer la valeur du frais
           $repository=$this->getDoctrine()->getRepository(Tarif::class);
           $commission=$repository->findAll();

           //recuperer la valeur du montant saisie
            $montant=$transaction->getMontant();

            //Verifier si le montant est disponible en solde 
            $comptes=$this->getUser()->getCompte();
            if($transaction->getMontant() >= $comptes->getSolde()){
                return $this->json([
                    'messagù.10e
                    18' => 'votre solde( '.$comptes->getSolde().' ) ne vous permez pas d\'effectuer cet envoie'
                ]);
               }
            

            // trouver les frais qui correspond au montant
           foreach ($commission as $values ) {
                 $values->getBorneInferieure();
                $values->getBorneSuperieure();
               $values->getValeur();
            if($montant >= $values->getBorneInferieure() &&  $montant <= $values->getBorneSuperieure()){
                $valeur=$values->getValeur();
            }

           }
           $transaction->setFrais($valeur);

           // repartition des commissions 
           $wari=($valeur*40)/100;
           $part=($valeur*20)/100;
           $etat=($valeur*30)/100;
           $retrait=($valeur*10)/100;

           // dimunition du monatnt envoyé au niveau du solde et ajout de la commission pour wari
           $comptes->setSolde($comptes->getSolde()-$transaction->getMontant()+ $wari);

           $transaction->setCommissionWari($wari);
           $transaction->setCommissionPartenaire($part);
           $transaction->setCommissionEtat($etat);
           $transaction->setCommissionRetrait($retrait);


        $total=$montant+$values->getValeur();
        $transaction->setTotal($total);
        $transaction->setEtat('envoye');

        $entityManager = $this->getDoctrine()->getManager();
     
        $entityManager->persist($transaction);
        $entityManager->flush();

            $data = [
               'status1' => 201,
               'message1' => 'L\'envoie  a été effectué'
           ];
           return new JsonResponse($data, 201);
        }
        $data = [
            'status1' => 500,
            'message1' => 'ERREUR, VERIFIER LES DONNÉES SAISIES'
        ];
        return new JsonResponse($data, 500);
    }

 /**
     * @Route("/retrait", name="retrait" ) 
     *@IsGranted({"ROLE_ADMIN_PARTENAIRE", "ROLE_USERS"})
     */
    public function retrait (Request $request,TransactionRepository $trans, EntityManagerInterface $entityManager)
    {
       $transaction= new Transaction();
        $form = $this->createForm(RetraitType::class, $transaction);
        $values =$request->request->all();
        $form->handleRequest($request);
        $form->submit($values);
       $codes=$transaction->getCode();


        $code=$trans->findOneBy(['code'=>$codes]);
                // var_dump($code); die();

        //$c=$code->getCode();
        $statut=$code->getEtat();
       //var_dump($statut);  die();
            if(!$code ){
                return new Response('Ce code est invalide ',Response::HTTP_CREATED);
            }
                else if($code->getCode()==$codes && $statut=="retire" ){
                    return new Response('Le code est déja retiré',Response::HTTP_CREATED);
                }
                
                
                    $user=$this->getUser();
                    $code->setGuichetierRetrait($user);
                    //$beneficiaire->setNumeroPiece($values)
                    $code->setEtat("retire");
                    $code->setDateRetrait(new \DateTime());
                    $code->setNumeroPieceb($values['numeroPieceb']);
                    $code->setTypePieceb($values['typePieceb']);
                    $entityManager->persist($code);
                    $entityManager->flush();
                return new Response('Retrait efféctué avec succés',Response::HTTP_CREATED);   
            

     }


    
     /**
     * @Route("/addCompte/{id}", name="ajou_compte", methods={"POST"})
     *@IsGranted({"ROLE_ADMIN_PARTENAIRE"})
     */
    public function update_user (Request $request, Utilisateur $user,  EntityManagerInterface $entityManager)
    {

        $form = $this->createForm(UserCompteType::class, $user);
        $data=$request->request->all();
        
        $form->handleRequest($request);
        $form->submit($data);
        //$users=$user->getCompte()->getPartenaire();
      //  var_dump($users); die();
        //$comptes=$user->getCompte();

        $entityManager->persist($user);
            $entityManager->flush();
            $data = [
                'status14' => 200,
                'message14' => 'Le compte a bien été  bien ajoute'
            ];
            return new JsonResponse($data);
    }


/**
     * @Route("/listercompte", name="listercompte", methods={"GET"})
     */
    public function lister(CompteRepository $compteRepository, SerializerInterface $serializer)
    {

        $comptes = $compteRepository->findAll();
        
        $data = $serializer->serialize($comptes, 'json');

        return new Response($data, 200, [
            'Content-Type'=>'application/json'
        ]);
    }

       /**
     * @Route("/listerdepot", name="listerdepot", methods={"GET"})
     */
    public function listerdepot(DepotRepository $depotRepository, SerializerInterface $serializer)
    {
        $depots = $depotRepository->findAll();
        
        $data = $serializer->serialize($depots, 'json');

        return new Response($data, 200, [
            'Content-Type'=>'application/json'
        ]);
    }

}
