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

            $user->setNom("part");
            $user->setUsername("part");
            $user->setPassword("part");
            $user->setEmail("jpart@gmail.com");
            $user->setCompte("Particulier");
            $user->setSalt('');
            $user->setRoles(array('ROLE_PARTICULIER'));

            $user2 = new Utilisateurs();
            $user2->setNom('admin')
                  ->setUsername('admin')
                  ->setPassword('admin')
                  ->setEmail('admin@gmail.com')
                  ->setCompte('Naturaliste')
                  ->setSalt('')
                  ->setVerifie(true)
                  ->setRoles(array('ROLE_ADMIN'));

            $user3 = new Utilisateurs();
            $user3->setNom('nature')
                  ->setUsername('nature')
                  ->setPassword('nature')
                  ->setEmail('nature@gmail.com')
                  ->setCompte('Naturaliste')
                  ->setVerifie(true)
                  ->setSalt('')
                  ->setRoles(array('ROLE_NATURALISTE'));

            $user4 = new Utilisateurs();
            $user4->setNom("test");
            $user4->setUsername("test");
            $user4->setPassword("test");
            $user4->setEmail("test@gmail.com");
            $user4->setCompte("Naturaliste");
            $user4->setSalt('');
            $user4->setRoles(array('ROLE_PARTICULIER'));


            $manager->persist($user);
            $manager->persist($user2);
            $manager->persist($user3);
            $manager->persist($user4);

            $manager->flush();
    }
}