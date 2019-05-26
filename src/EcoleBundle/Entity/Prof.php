<?php

namespace EcoleBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Prof.
 *
 * @ORM\Table(name="prof")
 * @ORM\Entity()
 */
class Prof
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var int
     *
     * @ORM\Column(name="cin", type="integer", unique=true)
     */
    private $cin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_naissance", type="datetime")
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string", length=255)
     */
    private $genre;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="competences", type="text")
     */
    private $competences;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_tel", type="integer")
     */
    private $numeroTel;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="debut", type="datetime")
     */
    private $debut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fin", type="datetime")
     */
    private $fin;

    /**
     * @ORM\OneToMany(targetEntity="EcoleBundle\Entity\Seance", mappedBy="prof")
     */
    private $seances;
    /**
     * @ORM\OneToMany(targetEntity="EcoleBundle\Entity\Evaluation", mappedBy="prof")
     */
    private $evalutions;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
        $this->evalutions = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return Prof
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom.
     *
     * @param string $prenom
     *
     * @return Prof
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom.
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set cin.
     *
     * @param int $cin
     *
     * @return Prof
     */
    public function setCin($cin)
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * Get cin.
     *
     * @return int
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     * Set dateNaissance.
     *
     * @param \DateTime $dateNaissance
     *
     * @return Prof
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance.
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set genre.
     *
     * @param string $genre
     *
     * @return Prof
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre.
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return Prof
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set competences.
     *
     * @param string $competences
     *
     * @return Prof
     */
    public function setCompetences($competences)
    {
        $this->competences = $competences;

        return $this;
    }

    /**
     * Get competences.
     *
     * @return string
     */
    public function getCompetences()
    {
        return $this->competences;
    }

    /**
     * Set adresse.
     *
     * @param string $adresse
     *
     * @return Prof
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse.
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set numeroTel.
     *
     * @param int $numeroTel
     *
     * @return Prof
     */
    public function setNumeroTel($numeroTel)
    {
        $this->numeroTel = $numeroTel;

        return $this;
    }

    /**
     * Get numeroTel.
     *
     * @return int
     */
    public function getNumeroTel()
    {
        return $this->numeroTel;
    }

    /**
     * Set debut.
     *
     * @param \DateTime $debut
     *
     * @return Prof
     */
    public function setDebut($debut)
    {
        $this->debut = $debut;

        return $this;
    }

    /**
     * Get debut.
     *
     * @return \DateTime
     */
    public function getDebut()
    {
        return $this->debut;
    }

    /**
     * Set fin.
     *
     * @param \DateTime $fin
     *
     * @return Prof
     */
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * Get fin.
     *
     * @return \DateTime
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Add seance.
     *
     * @param \EcoleBundle\Entity\Seance $seance
     *
     * @return Prof
     */
    public function addSeance(\EcoleBundle\Entity\Seance $seance)
    {
        $this->seances[] = $seance;

        return $this;
    }

    /**
     * Remove seance.
     *
     * @param \EcoleBundle\Entity\Seance $seance
     */
    public function removeSeance(\EcoleBundle\Entity\Seance $seance)
    {
        $this->seances->removeElement($seance);
    }

    /**
     * Get seances.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeances()
    {
        return $this->seances;
    }

    /**
     * Add evalution.
     *
     * @param \EcoleBundle\Entity\Evaluation $evalution
     *
     * @return Prof
     */
    public function addEvalution(\EcoleBundle\Entity\Evaluation $evalution)
    {
        $this->evalutions[] = $evalution;

        return $this;
    }

    /**
     * Remove evalution.
     *
     * @param \EcoleBundle\Entity\Evaluation $evalution
     */
    public function removeEvalution(\EcoleBundle\Entity\Evaluation $evalution)
    {
        $this->evalutions->removeElement($evalution);
    }

    /**
     * Get evalutions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvalutions()
    {
        return $this->evalutions;
    }
}
