<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiFixtures extends Fixture
{

private $encoder;

public function __construct(UserPasswordEncoderInterface $encoder)
{
    $this->encoder = $encoder;
}
    public function load(ObjectManager $manager)
    {
       
        $user = new Utilisateur();
        $user->setUsername('Kabirou');
        $mdp="123456";
        $user->setRoles(['ROLE_SUPER_ADMIN']);

        $password = $this->encoder->encodePassword($user,$mdp);
        $user->setPassword($password);
        $user->setPrenom('kabirou');
        $user->setNom('Mbodj');
        $user->setTelephone(76129635);
        $user->setImageName("image.jpg");
        $user->setUpdatedAt(new \DateTime('now'));



        $manager->persist($user);
        $manager->flush();
    }
}
