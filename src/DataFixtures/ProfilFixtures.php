<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProfilFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $libelle=array("superadmin","adminsuper","adminpartenaire","admin","caissier","user");
        
        for($i=0;$i<count($libelle);$i++){
            $profil=new Profil();
            $profil->setLibelle($libelle[$i]);
           
            $manager->persist($profil);
        }
        $manager->flush();
    }
}
