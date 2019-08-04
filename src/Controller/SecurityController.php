<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\Partenaire;
use App\Entity\Utilisateur;

use App\Form\UtilisateurType;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



/**
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
     /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager){
         
        $user = new Utilisateur();
         $form = $this->createForm(UtilisateurType::class, $user);

         $form->handleRequest($request);
         $values=$request->request->all();
         $form->submit($values);
         $files=$request->files->all()['imageName'];

        if ($form->isSubmitted() && $form->isValid()) {
             $user->setPassword(
                $passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
                )
                );

             $user->setImageFile($files);
            // recuperer id profil
            $repos=$this->getDoctrine()->getRepository(Profil::class);
            $profils=$repos->find($values['profil']);
            $user->setProfil($profils);

            $role=[];
            if($profils->getLibelle() == "admin"){
                $role=(["ROLE_ADMIN"]);
            }
            elseif($profils->getLibelle() == "user"){
                $role=(["ROLE_USER"]);
            }
            elseif($profils->getLibelle() == "caissier"){
                $role=(["ROLE_CAISSIER"]);
            }
            elseif( $profils->getLibelle() == "superadmin"){
                $role=(["ROLE_SUPER_ADMIN"]);
            }
            $user->setRoles($role);
            $user->setStatut("debloquer");

             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($user);
             $entityManager->flush();
             $data = [
                'status1' => 201,
                'message1' => 'L\'utilisateur a été créé'
            ];

            return new JsonResponse($data, 201);
         }
         

        $data = [
            'status2' => 500,
            'message2' => 'Vous devez renseigner les clés username et password'
        ];
        return new JsonResponse($data, 500);
    
    }
   
  
    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request)
    {

        $user = $this->getUser();
        return $this->json([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles()
        ]);
    }

   

        /**
     * @Route("/profils", name="add_profil", methods={"POST"})
     */
    public function addProfil(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $profil = $serializer->deserialize($request->getContent(), Profil::class, 'json');
        $entityManager->persist($profil);

        $entityManager->flush();
        $data = [
            'status' => 201,
            'message' => 'Le Profil a bien été ajouté'
        ];
        return new JsonResponse($data, 201);
    }


     /**
     * @Route("/users/bloquer", name="userBlock", methods={"GET","POST"})
     * @Route("/users/debloquer", name="userDeblock", methods={"GET","POST"})
     */
    public function userBloquer(Request $request, UtilisateurRepository $userRepo,EntityManagerInterface $entityManager): Response
    {
        $values = json_decode($request->getContent());
        $user=$userRepo->findOneByUsername($values->username);
        echo $user->getStatut();
        
        if($user->getStatut()=="bloquer"){
            if($user->getProfil()==5){
                $user->setRoles(["ROLE_ADMIN"]);
            }
            elseif($user->getProfil()=="superadmin"){
                $user->setRoles(["ROLE_SUPER_ADMIN"]);
            }
            elseif($user->getProfil()==6){
                $user->setRoles(["ROLE_USER"]);
            }
            elseif($user->getProfil()=="caissier"){
                $user->setRoles(["ROLE_CAISSIER"]);
            }
            $user->setStatut("debloquer");
        }
        else{
            $user->setStatut("bloquer");
            $user->setRoles(["ROLE_USERLOCK"]);
        }

        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'utilisateur a changé de statut (bloqué/débloqué)'
        ];
        return new JsonResponse($data);
    }




}
