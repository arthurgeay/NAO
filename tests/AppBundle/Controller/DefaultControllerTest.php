<?php

namespace tests\AppBundle\Tests\Controller;

use Omega\NAOBundle\Entity\Observations;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Omega\NAOBundle\Entity\Utilisateurs;

class DefaultControllerTest extends WebTestCase
{
    public function testCorrectMail()
    {
        $user = new Utilisateurs();
        $email = "julien.boulnois@gmail.com";
        $user->setEmail($email);
        $this->assertEquals($email, $user->getEmail());
    }

    public function testCorrectObservationEspece()
    {
        $obs = new Observations();
        $espece = "Épervier brun";
        $obs->setEspece($espece);
        $this->assertEquals($espece, $obs->getEspece());
    }

    public function testCorrectInscription()
    {
        $user = new Utilisateurs();
        $userName = "Gérard";
        $user->setUsername($userName);
        $this->assertEquals($userName, $user->getUsername());
    }
}