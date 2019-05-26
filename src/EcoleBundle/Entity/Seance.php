<?php

namespace EcoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seance.
 *
 * @ORM\Table(name="seance")
 * @ORM\Entity()
 */
class Seance
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
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="debut", type="datetime", nullable=true)
     */
    private $debut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $fin;
    /**
     * @var int
     *
     * @ORM\Column(name="jour", type="integer")
     */
    private $jour;
    /**
     * @ORM\JoinColumn( onDelete="SET NULL")
     * @ORM\ManyToOne(targetEntity="EcoleBundle\Entity\Classe", inversedBy="lesseances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $classe;

    /**
     * @ORM\JoinColumn( onDelete="SET NULL")
     * @ORM\ManyToOne(targetEntity="EcoleBundle\Entity\Prof", inversedBy="seances")
     */
    private $prof;

    public function setClasse(Classe $classe)
    {
        $this->classe = $classe;

        return $this;
    }

    public function getClasse()
    {
        return $this->classe;
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
     * @return Seance
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
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Seance
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set prof.
     *
     * @param string $prof
     *
     * @return Seance
     */
    public function setProf($prof)
    {
        $this->prof = $prof;

        return $this;
    }

    /**
     * Get prof.
     *
     * @return string
     */
    public function getProf()
    {
        return $this->prof;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Seance
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set debut.
     *
     * @param \DateTime $debut
     *
     * @return Seance
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
     * @return Seance
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
     * Set jour.
     *
     * @param int $jour
     *
     * @return Seance
     */
    public function setJour($jour)
    {
        $this->jour = $jour;

        return $this;
    }

    /**
     * Get jour.
     *
     * @return int
     */
    public function getJour()
    {
        return $this->jour;
    }
}
