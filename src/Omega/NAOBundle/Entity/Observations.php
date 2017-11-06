<?php

namespace Omega\NAOBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Omega\NAOBundle\Validator\Verifespece;

/**
 * Observations
 *
 * @ORM\Table(name="observations")
 * @ORM\Entity(repositoryClass="Omega\NAOBundle\Repository\ObservationsRepository")
 */
class Observations
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="espece", type="string", length=255)
     * @Assert\NotBlank(message="Ce champ ne peut être vide")
     * @Verifespece()
     */
    private $espece;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text")
     * @Assert\NotBlank(message="Ce champ ne peut être vide")
     */
    private $commentaire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\Date(message="Veuillez insérer une date valide")
     * @Assert\LessThanOrEqual("today", message="La date d'observation doit être inférieure ou égale à aujourd'hui.")
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="verifie", type="boolean")
     */
    private $verifie = false;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     * @Assert\NotBlank(message="Ce champ ne peut être vide")
     * @Assert\Type(type="float", message="Cette valeur n'est pas une longitude")
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     * @Assert\NotBlank(message="Ce champ ne peut être vide")
     * @Assert\Type(type="float", message="Cette valeur n'est pas une longitude")
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", nullable=true)
     * @Assert\Image(mimeTypesMessage="Ce fichier n'est pas une image valide",
     * mimeTypes = {"image/jpeg"},
     * maxSize = "1024k")
     */
    private $photo;

    /**
     * @ORM\OneToOne(targetEntity="Omega\NAOBundle\Entity\Utilisateurs", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set espece
     *
     * @param string $espece
     *
     * @return Observations
     */
    public function setEspece($espece)
    {
        $this->espece = $espece;

        return $this;
    }

    /**
     * Get espece
     *
     * @return string
     */
    public function getEspece()
    {
        return $this->espece;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Observations
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Observations
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Observations
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Observations
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Observations
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set verifie
     *
     * @param boolean $verifie
     *
     * @return Observations
     */
    public function setVerifie($verifie)
    {
        $this->verifie = $verifie;

        return $this;
    }

    /**
     * Get verifie
     *
     * @return boolean
     */
    public function getVerifie()
    {
        return $this->verifie;
    }

    /**
     * Set utilisateur
     *
     * @param \Omega\NAOBundle\Entity\Utilisateurs $utilisateur
     *
     * @return Observations
     */
    public function setUtilisateur(\Omega\NAOBundle\Entity\Utilisateurs $utilisateur)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \Omega\NAOBundle\Entity\Utilisateurs
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set utilisateurs
     *
     * @param \Omega\NAOBundle\Entity\Utilisateurs $utilisateurs
     *
     * @return Observations
     */
    public function setUtilisateurs(\Omega\NAOBundle\Entity\Utilisateurs $utilisateurs)
    {
        $this->utilisateurs = $utilisateurs;

        return $this;
    }

    /**
     * Get utilisateurs
     *
     * @return \Omega\NAOBundle\Entity\Utilisateurs
     */
    public function getUtilisateurs()
    {
        return $this->utilisateurs;
    }
}
