<?php

/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 01/11/2017
 * Time: 14:49
 */
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Omega\NAOBundle\Entity\Utilisateurs;

class LoadUser implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

            $user = new Utilisateurs();

            $user->setNom("Boulnois");
            $user->setUsername("Julien");
            $user->setPassword("Password");
            $user->setEmail("julien.boulnois12@gmail.com");
            $user->setCompte("Naturaliste");
            $user->setSalt('');
            $user->setRoles(array('ROLE_USER'));

            $manager->persist($user);

            $manager->flush();
    }
}