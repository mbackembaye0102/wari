<?php

namespace App\Controller;

use App\Entity\Partenaire;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use App\Repository\PartenaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/api")
 */
class WariController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $values = json_decode($request->getContent());
        if(isset($values->username,$values->password)) {
            $user = new Utilisateur();
            $user->setUsername($values->username);
            $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
            $user->setProfil($values->profil);
            $profil=$user->getProfil();
            $role=[];
            if($profil == "admin"){
              $role=["ROLE_ADMIN"];  
            }
            elseif($profil == "superadmin"){
                $role=["ROLE_SUPER_ADMIN"];
            }
            elseif($profil == "user"){
                $role=["ROLE_USER"];
            }
            $user->setRoles($role);
            $user->setPrenom($values->prenom);
            $user->setNom($values->nom);
            $user->setTelephone($values->telephone);
            $user->setMail($values->mail);
            $user->setAdresse($values->adresse);
            $user->setCni($values->cni);
            $user->setStatut($values->statut);
            

            $repo=$this->getDoctrine()->getRepository(Partenaire::class);
            $partenaires=$repo->find($values->partenaire);
            $user->setPartenaire($partenaires);
     


            $entityManager->persist($user);
            $entityManager->flush();

            $data = [
                'status1' => 201,
                'message1' => 'L\'utilisateur a été créé'
            ];

            return new JsonResponse($data, 201);
        }
        $data = [
            'status' => 500,
            'message' => 'Vous devez renseigner les clés username et password'
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
     * @Route("/users/bloquer", name="userBlock", methods={"GET","POST"})
     * @Route("/users/debloquer", name="userDeblock", methods={"GET","POST"})
     */

    public function userBloquer(Request $request, UtilisateurRepository $userRepo,EntityManagerInterface $entityManager): Response
    {
        $values = json_decode($request->getContent());
        $user=$userRepo->findOneByUsername($values->username);
        echo $user->getStatut();
        if($user->getStatut()=="bloquer"){
            if($user->getProfil()=="admin"){
                $user->setRoles(["ROLE_ADMIN"]);
            }
            elseif($user->getProfil()=="superadmin"){
                $user->setRoles(["ROLE_SUPER_ADMIN"]);
            }
            elseif($user->getProfil()=="user"){
                $user->setRoles(["ROLE_USER"]);
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












<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ApiFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('kabirou');
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $password = $this->encoder->encodePassword($user, '123456');
        $user->setPassword($password);
        $user->set('kabirou');
        $user->setUsername('kabirou');
        $user->setUsername('kabirou');
        $user->setUsername('kabirou');
        $manager->persist($user);
        $manager->flush();
    }
}





function random($car) {
	$string = "";
	$chaine = "ABCDEFGHIJQLMNOPQRSTUVWXYZabcdefghijqlmnopqrstuvwxyz0123456789";
	srand((double)microtime()*1000000);
	for($i=0; $i<$car; $i++) {
		$string .= $chaine[rand()%strlen($chaine)];
	}
	return $string;
}
 
$mdp = random(8);



public function createPassword($nbCaractere)
    {
        $password = "";
        for($i = 0; $i <= $nbCaractere; $i++)
        {
            $random = rand(97,122);
            $password .= chr($random);
        }
 
        return $password;
    }
