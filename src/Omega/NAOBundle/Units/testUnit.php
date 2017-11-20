<?php


class testUnit 
{
    public function testCorrectMail()
    {
        $user = new \Omega\NAOBundle\Entity\Utilisateurs();
        $email = "julien.boulnois12@gmail.com";
        $user->setEmail($email);
        $this->assertEquals($email, $user->getEmail());
    }
}