<?php

namespace EcoleBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Etudiant.
 *
 * @ORM\Table(name="etudiant")
 * @ORM\Entity()
 */
class Etudiant
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
     * @var bool
     */
    private $status;

    /**
     * @var date
     *
     * @ORM\Column(name="date_naissance", type="date", length=255)
     */
    private $date_naissance;
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;
    /**
     * @var string
     *
     * @ORM\Column(name="nom_pere", type="string", length=255)
     */
    private $nom_pere;
    /**
     * @var string
     *
     * @ORM\Column(name="nom_mere", type="string", length=255)
     */
    private $nom_mere;
    /**
     * @var string
     *
     * @ORM\Column(name="numero_tel", type="integer", length=255)
     */
    private $numero_tel;

    /**
     * @var string
     *
     * @ORM\Column(name=" genre", type="string", length=255)
     */
    private $genre;
    /**
     * @ORM\ManyToOne(targetEntity="EcoleBundle\Entity\Classe", inversedBy="lesetudiants")
     * @ORM\JoinColumn( onDelete="SET NULL")
     * @ORM\JoinColumn(nullable=false)
     */
    private $classe;
    /**
     * @ORM\OneToMany(targetEntity="EcoleBundle\Entity\Note", mappedBy="etudiant")
     */
    private $notes;
    /**
     * @ORM\OneToMany(targetEntity="EcoleBundle\Entity\Absence", mappedBy="etudiant")
     */
    private $ab;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->ab = new ArrayCollection();
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
     * @return Etudiant
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
     * @return Etudiant
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
     * Set classe.
     *
     * @param \EcoleBundle\Entity\Classe $classe
     *
     * @return Etudiant
     */
    public function setClasse(\EcoleBundle\Entity\Classe $classe = null)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe.
     *
     * @return \EcoleBundle\Entity\Classe
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Add note.
     *
     * @param \EcoleBundle\Entity\Note $note
     *
     * @return Etudiant
     */
    public function addNote(\EcoleBundle\Entity\Note $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * Remove note.
     *
     * @param \EcoleBundle\Entity\Note $note
     */
    public function removeNote(\EcoleBundle\Entity\Note $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set dateNaissance.
     *
     * @param \DateTime $dateNaissance
     *
     * @return Etudiant
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->date_naissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance.
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->date_naissance;
    }

    /**
     * Set adresse.
     *
     * @param string $adresse
     *
     * @return Etudiant
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
     * Set nomPere.
     *
     * @param string $nomPere
     *
     * @return Etudiant
     */
    public function setNomPere($nomPere)
    {
        $this->nom_pere = $nomPere;

        return $this;
    }

    /**
     * Get nomPere.
     *
     * @return string
     */
    public function getNomPere()
    {
        return $this->nom_pere;
    }

    /**
     * Set nomMere.
     *
     * @param string $nomMere
     *
     * @return Etudiant
     */
    public function setNomMere($nomMere)
    {
        $this->nom_mere = $nomMere;

        return $this;
    }

    /**
     * Get nomMere.
     *
     * @return string
     */
    public function getNomMere()
    {
        return $this->nom_mere;
    }

    /**
     * Set numeroTel.
     */
    public function setNumeroTel($numeroTel)
    {
        $this->numero_tel = $numeroTel;

        return $this;
    }

    /**
     * Get numeroTel.
     *
     * @return \int
     */
    public function getNumeroTel()
    {
        return $this->numero_tel;
    }

    /**
     * Set genre.
     *
     * @param string $genre
     *
     * @return Etudiant
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

    public function setStatus($boolean)
    {
        $boolean = (bool) $boolean;
        if (true == $boolean) {
            $this->status = 1;
        // code...
        } else {
            $this->status = 0;
        }

        return $this;
    }

    /**
     * Get status.
     *
     * @return \int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add ab.
     *
     * @param \EcoleBundle\Entity\Absence $ab
     *
     * @return Etudiant
     */
    public function addAb(\EcoleBundle\Entity\Absence $ab)
    {
        $this->ab[] = $ab;

        return $this;
    }

    /**
     * Remove ab.
     *
     * @param \EcoleBundle\Entity\Absence $ab
     */
    public function removeAb(\EcoleBundle\Entity\Absence $ab)
    {
        $this->ab->removeElement($ab);
    }

    /**
     * Get ab.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAb()
    {
        return $this->ab;
    }
}
