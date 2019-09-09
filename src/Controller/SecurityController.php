<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Form\ProfilType;
use App\Form\RetraitType;

use App\Entity\Partenaire;
use App\Entity\Transaction;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\PartenaireRepository;
use App\Repository\ProfilRepository;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;

/**
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
     /**
     * @Route("/register", name="register", methods={"POST"})
     *@IsGranted({"ROLE_ADMIN_PARTENAIRE", "ROLE_SUPER_ADMIN"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer){
         
        $user = new Utilisateur();
         $form = $this->createForm(UtilisateurType::class, $user);
         $form->handleRequest($request);
         $values=$request->request->all();
         $form->submit($values);
         $files=$request->files->all()['imageName'];

        if ($form->isSubmitted()){
            $mdp="123456";
             $user->setPassword(
                $passwordEncoder->encodePassword(
                $user, $mdp  )
                );
             $user->setImageFile($files);

            // recuperer id profil
            $repos=$this->getDoctrine()->getRepository(Profil::class);
            $profils=$repos->find($values['profil']);
            $user->setProfil($profils);

            $role=[];
            if($profils->getLibelle() == "admin"){
                if($this->getUser()->getRoles()[0]!='ROLE_SUPER_ADMIN' 
                && $this->getUser()->getRoles()[0]!='ROLE_ADMIN' ){
                    return $this->json([
                        'message188' => 'Vous n\'avez pas les droits de creer un admin'
                    ]);
                }
                $role=(["ROLE_ADMIN"]);
            }
            
            elseif($profils->getLibelle() == "user"){
                if($this->getUser()->getRoles()[0]!='ROLE_ADMIN_PARTENAIRE'
                && $this->getUser()->getRoles()[0]!='ROLE_ADMIN'){
                    return $this->json([
                        'message187' => 'Vous n\'avez pas les droits de creer un user simple'
                    ]);
                }
                $role=(["ROLE_USER"]);
            }
            elseif($profils->getLibelle() == "caissier"){   
                
                if($this->getUser()->getRoles()[0]!='ROLE_SUPER_ADMIN'
                && $this->getUser()->getRoles()[0]!='ROLE_ADMIN_SUPER'){
                    return $this->json([
                        'message18' => 'Vous n\'avez pas les droits de creer un caissier'
                    ]);
                }
                $role=(["ROLE_CAISSIER"]);
            }
            elseif( $profils->getLibelle() == "superadmin"){
                if($this->getUser()->getRoles()[0]!='ROLE_SUPER_ADMIN'){
                    return $this->json([
                        'message18' => 'Vous n\'avez pas les droits de creer un super admin'
                    ]);
                }
                $role=(["ROLE_SUPER_ADMIN"]);
            }
            elseif( $profils->getLibelle() == "adminpartenaire"){
                if($this->getUser()->getRoles()[0]!='ROLE_SUPER_ADMIN'
                && $this->getUser()->getRoles()[0]!='ROLE_ADMIN_SUPER' ){
                    return $this->json([
                        'message18' => 'Vous n\'avez pas les droits de creer un admin partenaire'
                    ]);
                }
                $role=(["ROLE_ADMIN_PARTENAIRE"]);
            }
            elseif( $profils->getLibelle() == "adminsuper"){
                if($this->getUser()->getRoles()[0]!='ROLE_SUPER_ADMIN'
                && $this->getUser()->getRoles()[0]!='ROLE_ADMIN_SUPER'){
                    return $this->json([
                        'message18' => 'Vous n\'avez pas les droits de creer un admin super'
                    ]);
                }
                $role=(["ROLE_ADMIN_SUPER"]);
            }
            $user->setRoles($role);
            $user->setStatut("debloquer");
            // recup id partenaire
            $users=$this->getUser()->getPartenaire();
            $user->setPartenaire($users);

            $errors=$validator->validate($user);
            if(count($errors)){
                $errors=$serializer->serialize($errors, 'json');
                return new Response ($errors, 500,[
                    'content_type'=>'application/json'
                ]);
            }
            var_dump($user); die();
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($user);
             $entityManager->flush();
             $data = [
                'status18' => 201,
                'message18' => 'L\'utilisateur a été créé'
            ];
            return new JsonResponse($data, 201);
         }
        $data = [
            'status2' => 500,
            'message2' => 'Vous devez renseigner les clés username et password'
        ];
        return new JsonResponse($data, 500);
    }


    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
      $this->encoder = $encoder;
    }
    

    /**
     * @Route("/login", name="login", methods={"POST"})
    * @param JWTEncoderInterface $JWTEncoder
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function login(Request $request,JWTEncoderInterface $JWTEncoder)
    {
        $values = json_decode($request->getContent());        
        $repo = $this->getDoctrine()->getRepository(Utilisateur::class);
        $user = $repo-> findOneBy(['username' => $values->username]);
    if($user->getStatut()!=null && $user->getRoles()!="ROLE_SUPER_ADMIN" && $user->getPartenaire()!=null){
        if( $user->getStatut()=="bloquer"){
            return $this->json([
                'message10'=> $user->getUsername().' Nous sommes désolé, ACCÉS REFUSÉ'
            ]);
        }
        elseif( $user->getPartenaire()->getStatut()=="bloquer"){
            return $this->json([
                'message10' => 'ACCÉS REFUSÉ, Votre partenaire  du nom de '.$user->getPartenaire()->getEntreprise().'  est bloqué'
            ]);
        }
    }
        
        $token = $JWTEncoder->encode([
            'username' => $user->getUsername(),
	'roles'=>$user->getRoles(),
            'exp' => time() + 86400 // 1 day expiration
        ]);
        return $this->json([
            'token' => $token
        ]);
    }

    /**
     * @Route("/profils", name="add_profil", methods={"POST"})
     */
    public function addProfil(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $profil = new Profil();
        $form = $this->createForm(ProfilType::class, $profil);

        $form->handleRequest($request);
        $values=$request->request->all();
        $form->submit($values);

        if ($form->isSubmitted()) {

           
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($profil);
           $entityManager->flush();
           
            $data = [
               'status1' => 201,
               'message16' => 'Le Profil a été créé'
           ];
           return new JsonResponse($data, 201);
        }
        $data = [
            'status1' => 500,
            'message14' => 'L\'insertion à echoué'
        ];
        return new JsonResponse($data, 500);
    }

     /**
     * @Route("/users/statut/{id}", name="userBlock", methods={"GET","POST"})
     */
    public function userBloquer( Utilisateur $users, Request $request, UtilisateurRepository $userRepo,EntityManagerInterface $entityManager): Response
    {
        
        $values = json_decode($request->getContent());
                //var_dump($users->getId());  die();

        $user=$userRepo->find($users->getId());
        //echo $user->getStatut();

        if($user->getUsername()== "Kabirou"){
            
            return $this->json([
                'message1' =>'HEE KHANAA DAGUAA DOF KI SUPER ADMIN LEU, KENE DOUKO BLOKÉ'
            ]);
           
        }
        elseif($user->getStatut()=="bloquer"){
            $user->setStatut("debloquer");
            $entityManager->flush();

            return $this->json([
                'message1' =>$user->getUsername()."  vous etes débloqué"
            ]);
           
        }
        
        else{
            $user->setStatut("bloquer");
            $entityManager->flush();
            return $this->json([
                'message1' =>$user->getUsername()."  vous etes bloqué"
            ]);
        }
       
    }


 


/**
     * @Route("/listerprofil", name="listerprofil", methods={"GET"})
     */
    public function lister(ProfilRepository $profilRepository, SerializerInterface $serializer)
    {
        $profils = $profilRepository->findAll();
        
        $data = $serializer->serialize($profils, 'json');

        return new Response($data, 200, [
            'Content-Type'=>'application/json'
        ]);
    }

       




}
