<?php

namespace EcoleBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Classe.
 *
 * @ORM\Table(name="classe")
 * @ORM\Entity()
 */
class Classe
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
     * @var string
     *
     * @ORM\Column(name="annee", type="string", length=255, nullable=true)
     */
    private $annee;

    /**
     * @ORM\OneToMany(targetEntity="EcoleBundle\Entity\Seance", mappedBy="classe")
     */
    private $lesseances;
    /**
     * @ORM\OneToMany(targetEntity="EcoleBundle\Entity\Etudiant", mappedBy="classe", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $lesetudiants;

    /**
     * @ORM\OneToMany(targetEntity="EcoleBundle\Entity\Evaluation", mappedBy="classe")
     */
    private $lesevaluations;

    /**
     * @ORM\OneToMany(targetEntity="EcoleBundle\Entity\Exercice", mappedBy="classe")
     */
    private $exercice;

    /**
     * @ORM\OneToMany(targetEntity="EcoleBundle\Entity\Note", mappedBy="classe")
     */
    private $notes;

    public function __construct()
    {
        $this->lesseances = new ArrayCollection();
        $this->lesevaluations = new ArrayCollection();
        $this->lesetudiants = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->exercice = new ArrayCollection();
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
     * Set lesseances.
     *
     * @param string $lesseances
     *
     * @return Classe
     */
    public function setLesseances($lesseances)
    {
        $this->lesseances = $lesseances;

        return $this;
    }

    /**
     * Get lesseances.
     *
     * @return string
     */
    public function getLesseances()
    {
        return $this->lesseances;
    }

    /**
     * Add lesseance.
     *
     * @param \EcoleBundle\Entity\Seance $lesseance
     *
     * @return Classe
     */
    public function addLesseance(\EcoleBundle\Entity\Seance $lesseance)
    {
        $this->lesseances[] = $lesseance;

        return $this;
    }

    /**
     * Remove lesseance.
     *
     * @param \EcoleBundle\Entity\Seance $lesseance
     */
    public function removeLesseance(\EcoleBundle\Entity\Seance $lesseance)
    {
        $this->lesseances->removeElement($lesseance);
    }

    /**
     * Add lesevaluations.
     *
     * @param \EcoleBundle\Entity\Test $lesevaluations
     *
     * @return Classe
     */
    public function addLesevaluations(\EcoleBundle\Entity\Evaluation $lesevaluations)
    {
        $this->lesevaluations[] = $lesevaluations;

        return $this;
    }

    /**
     * Remove lesevaluations.
     *
     * @param \EcoleBundle\Entity\Test $lesevaluations
     */
    public function removeLesevaluations(\EcoleBundle\Entity\Evaluation $lesevaluations)
    {
        $this->lesevaluations->removeElement($lesevaluations);
    }

    /**
     * Get lesevaluations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLesevaluations()
    {
        return $this->lesevaluations;
    }

    /**
     * Add lesetudiant.
     *
     * @param \EcoleBundle\Entity\Etudiant $lesetudiant
     *
     * @return Classe
     */
    public function addLesetudiant(\EcoleBundle\Entity\Etudiant $lesetudiant)
    {
        $this->lesetudiants[] = $lesetudiant;
        $lesetudiant->setClasse($this);

        return $this;
    }

    /**
     * Remove lesetudiant.
     *
     * @param \EcoleBundle\Entity\Etudiant $lesetudiant
     */
    public function removeLesetudiant(\EcoleBundle\Entity\Etudiant $lesetudiant)
    {
        $this->lesetudiants->removeElement($lesetudiant);
    }

    /**
     * @return ArrayCollection|Etudiant[]
     */
    public function getLesetudiants()
    {
        return $this->lesetudiants;
    }

    /**
     * Add lesevaluation.
     *
     * @param \EcoleBundle\Entity\Evaluation $lesevaluation
     *
     * @return Classe
     */
    public function addLesevaluation(\EcoleBundle\Entity\Evaluation $lesevaluation)
    {
        $this->lesevaluations[] = $lesevaluation;

        return $this;
    }

    /**
     * Remove lesevaluation.
     *
     * @param \EcoleBundle\Entity\Test $lesevaluation
     */
    public function removeLesevaluation(\EcoleBundle\Entity\Evaluation $lesevaluation)
    {
        $this->lesevaluations->removeElement($lesevaluation);
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Classe
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
     * Set annee.
     *
     * @param string $annee
     *
     * @return Classe
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee.
     *
     * @return string
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Add note.
     *
     * @param \EcoleBundle\Entity\Note $note
     *
     * @return Classe
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
     * Add exercice.
     *
     * @param \EcoleBundle\Entity\Exercice $exercice
     *
     * @return Classe
     */
    public function addExercice(\EcoleBundle\Entity\Exercice $exercice)
    {
        $this->exercice[] = $exercice;

        return $this;
    }

    /**
     * Remove exercice.
     *
     * @param \EcoleBundle\Entity\Exercice $exercice
     */
    public function removeExercice(\EcoleBundle\Entity\Exercice $exercice)
    {
        $this->exercice->removeElement($exercice);
    }

    /**
     * Get exercice.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExercice()
    {
        return $this->exercice;
    }
}
