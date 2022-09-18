<?php

namespace App\DataFixtures;

use App\Entity\Departement;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PersonneFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $departement = new Departement();
        $departement
            ->setNom('Haute-Garonne')
            ->setNumero(31);
        $manager->persist($departement);

        $departement2 = new Departement();
        $departement2
            ->setNom('Ile de France')
            ->setNumero(75);
        $manager->persist($departement2);

        $lherm = new Ville();
        $lherm
            ->setNom('Lherm')
            ->setCodePostal(31600)
            ->setDepartement($departement);
        $manager->persist($lherm);

        $muret = new Ville();
        $muret
            ->setNom('Muret')
            ->setCodePostal(31400)
            ->setDepartement($departement);
        $manager->persist($muret);

        $paris = new Ville();
        $paris
            ->setNom('Paris')
            ->setCodePostal(75000)
            ->setDepartement($departement2);
        $manager->persist($paris);

        $manager->flush();
    }
}
