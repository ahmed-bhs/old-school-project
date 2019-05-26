<?php

namespace EcoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evaluation.
 *
 * @ORM\Table(name="evaluation")
 * @ORM\Entity()
 */
class Evaluation
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
    private $description;
    /**
     * @var string
     *
     * @ORM\Column(name="semestre", type="string", length=255)
     */
    private $semestre;
    /**
     * @var float
     * @ORM\Column(name="coef", type="float", length=255)
     */
    private $coef;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    /**
     * @ORM\JoinColumn( onDelete="SET NULL")
     * @ORM\ManyToOne(targetEntity="EcoleBundle\Entity\Classe", inversedBy="lesevaluations")
     */
    private $classe;

    /**
     * @ORM\JoinColumn( onDelete="SET NULL")
     * @ORM\ManyToOne(targetEntity="EcoleBundle\Entity\Prof", inversedBy="evalutions")
     */
    private $prof;

    /**
     * @ORM\OneToMany(targetEntity="EcoleBundle\Entity\Note", mappedBy="evaluation")
     */
    private $notes;

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
     * @return Test
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
     * @return Test
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
     * Set classe.
     *
     * @param \EcoleBundle\Entity\Classe $classe
     *
     * @return Test
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
     * Constructor.
     */
    public function __construct()
    {
        $this->notes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Evaluation
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
     * Add note.
     *
     * @param \EcoleBundle\Entity\Note $note
     *
     * @return Evaluation
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
     * Set coef.
     *
     * @param float $coef
     *
     * @return Evaluation
     */
    public function setCoef($coef)
    {
        $this->coef = $coef;

        return $this;
    }

    /**
     * Get coef.
     *
     * @return float
     */
    public function getCoef()
    {
        return $this->coef;
    }

    /**
     * Set prof.
     *
     * @param \EcoleBundle\Entity\Prof $prof
     *
     * @return Evaluation
     */
    public function setProf(\EcoleBundle\Entity\Prof $prof = null)
    {
        $this->prof = $prof;

        return $this;
    }

    /**
     * Get prof.
     *
     * @return \EcoleBundle\Entity\Prof
     */
    public function getProf()
    {
        return $this->prof;
    }

    /**
     * Set semestre.
     *
     * @param string $semestre
     *
     * @return Evaluation
     */
    public function setSemestre($semestre)
    {
        $this->semestre = $semestre;

        return $this;
    }

    /**
     * Get semestre.
     *
     * @return string
     */
    public function getSemestre()
    {
        return $this->semestre;
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
