<?php

namespace App\Controller;


use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Compte;
use App\Entity\Profil;
use App\Form\CompteType;
use App\Entity\Partenaire;
use App\Entity\Utilisateur;
use App\Controller\UserType;
use App\Form\PartenaireType;
use App\Form\UtilisateurType;
use Symfony\Component\Mime\Message;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api")
 */
class PartenaireController extends AbstractController
{

    /**
     * @Route("/", name="index_partenaire", methods={"GET"})
     */
    public function index()
    {
        // Configurez Dompdf selon vos besoins
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instancier Dompdf avec nos options
        $dompdf = new Dompdf($pdfOptions);
        
        // Récupère le code HTML généré dans notre fichier twig
        $html = $this->renderView('partenaire/index.html.twig', [
            'title' => "Welcome to our PDF Test"
        ]);
        
        // Charger du HTML dans Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        
   
        //Exporter le PDF généré dans le navigateur (vue intégrée)
        $dompdf->stream("testpdf.pdf", [
            "Attachment" => false
        ]);

    }
    
    /**
     * @Route("/partenaire/{id}", name="show_partenaire", methods={"GET"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function show(Partenaire $partenaire, PartenaireRepository $partenaireRepository, SerializerInterface $serializer)
    {
        $partenaire = $partenaireRepository->find($partenaire->getId());
        $data = $serializer->serialize($partenaire, 'json', [
            'groups' => ['show']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    } 

  

    /**
     * @Route("/partenaires", name="add_partenaire", methods={"POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function addPartenaire(Request $request, UserPasswordEncoderInterface $passwordEncoder,EntityManagerInterface $entityManager,ValidatorInterface $validator, SerializerInterface $serializer){
         
        $partenaire = new Partenaire();
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $values=$request->request->all();

        $form->handleRequest($request);
        $form->submit($values);
        if ($form->isSubmitted()) {

            $partenaire->setStatut("debloquer");
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($partenaire);
           $entityManager->flush();

        //AJOUT ADMIN PARTENAIRE
        $user = new Utilisateur();
         $form = $this->createForm(UtilisateurType::class, $user);
         $form->handleRequest($request);

         $values=$request->request->all();
         $form->submit($values);
         $files=$request->files->all()['imageName'];
            $mdp="123456";
             $user->setPassword(
                $passwordEncoder->encodePassword(
                $user,$mdp)
                );

             $user->setImageFile($files);
         
            $user->setRoles(["ROLE_ADMIN"]);
            $user->setStatut("debloquer");

            // recuperer id du partenaire
                 $repos=$this->getDoctrine()->getRepository(Partenaire::class);
                 $partenaires=$repos->find($partenaire->getId());
                 $user->setPartenaire($partenaires);
                 $errors=$validator->validate($user);
                 if(count($errors)){
                     $errors=$serializer->serialize($errors, 'json');
                     return new Response ($errors, 500,[
                         'content_type'=>'application/json'
                     ]);
                 }
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($user);
             $entityManager->flush();


             /// AJOUT COMPTE PARTENAIRE
             $compte = new Compte();
             $form = $this->createForm(CompteType::class, $compte);
             $values = json_decode($request->getContent(), true);
             $form->handleRequest($request);
            $form->submit($values);
     
     
                 $a="SA";
                 $b=rand(1000000000000,9999999999999);
                 $numerocompte=$a.$b;
                 $compte->setNumeroCompte($numerocompte);
                 $compte->setSolde(1);
                
                   // recuperer id du partenaire
                 $repos=$this->getDoctrine()->getRepository(Partenaire::class);
                 $partenaires=$repos->find($partenaire->getId());
                 $compte->setPartenaire($partenaires);
     
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($compte);
                $entityManager->persist($user);
                $entityManager->persist($partenaire);

                $entityManager->flush();

             $data = [
                'status1' => 201,
                'message1' => 'Le partenaire , son admin avec son compte ont été bien cree '
            ];
 
            return new JsonResponse($data, 201);

    
    }
    $data = [
        'status1' => 500,
        'message1' => 'Vous devez renseigner tous les champs '
    ];
    return new JsonResponse($data, 500);
    }
     /**
     * @Route("/partenaires/{id}", name="update_partenaire", methods={"PUT"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function update(Request $request, SerializerInterface $serializer, Partenaire $partenaire, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $partenaireUpdate = $entityManager->getRepository(Partenaire::class)->find($partenaire->getId());
        $data=$request->request->all();
        foreach ($data as $key => $value){
            if($key && !empty($value)) {
                $name = ucfirst($key);
                $setter = 'set'.$name;
                $partenaireUpdate->$setter($value);
            }
        }
        $errors = $validator->validate($partenaireUpdate);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $entityManager->flush();
        $data = [
            'status14' => 200,
            'message14' => 'Le Partenaire a bien été mis à jour'
        ];
        return new JsonResponse($data);
    }

    /**
     * @Route("/partenaires/bloquer", name="partenaireBlock", methods={"GET","POST"})
     * @Route("/partenaires/debloquer", name="partenaireDeblock", methods={"GET","POST"})
     */

    public function partenaireBloquer(Request $request, PartenaireRepository $partenaireRepo,EntityManagerInterface $entityManager): Response
    {
        $values = json_decode($request->getContent());
        $partenaire=$partenaireRepo->findOneByEntreprise($values->entreprise);
        echo $partenaire->getStatut();
        
        if($partenaire->getStatut()=="bloquer"){
            
            $partenaire->setStatut("debloquer");
            $entityManager->flush();
            $data = [
                'status' => 200,
                'message' => 'Partenaire a été débloqué'
            ];
            return new JsonResponse($data);
        }
        else{
            $partenaire->setStatut("bloquer");
            $entityManager->flush();
            $data = [
                'status' => 200,
                'message' => 'Partenaire a été bloqué'
            ];
            return new JsonResponse($data);
        }
       
    }

}
