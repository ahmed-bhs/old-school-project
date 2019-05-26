<?php

namespace EcoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Note.
 * @ORM\Entity
 * @UniqueEntity(fields={"evaluation","etudiant"})
 * @ORM\Table(name="note")
 */
class Note
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
     * @var float
     *
     * @ORM\Column(name="valeur", type="float", nullable=true)
     */
    private $valeur;

    /**
     * @ORM\JoinColumn( onDelete="SET NULL")
     * @ORM\ManyToOne(targetEntity="EcoleBundle\Entity\Classe", inversedBy="notes")
     */
    private $classe;

    /**
     * @ORM\JoinColumn( onDelete="SET NULL")
     * @ORM\ManyToOne(targetEntity="EcoleBundle\Entity\Evaluation", inversedBy="notes")
     */
    private $evaluation;
    /**
     * @ORM\JoinColumn( onDelete="SET NULL")
     * @ORM\ManyToOne(targetEntity="EcoleBundle\Entity\Etudiant", inversedBy="notes")
     */
    private $etudiant;

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
     * Set valeur.
     *
     * @param float $valeur
     *
     * @return Note
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur.
     *
     * @return float
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set etudiant.
     *
     * @param \EcoleBundle\Entity\Etudiant $etudiant
     *
     * @return Note
     */
    public function setEtudiant(\EcoleBundle\Entity\Etudiant $etudiant = null)
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    /**
     * Get etudiant.
     *
     * @return \EcoleBundle\Entity\Etudiant
     */
    public function getEtudiant()
    {
        return $this->etudiant;
    }

    /**
     * Set evaluationId.
     *
     * @param \EcoleBundle\Entity\Evaluation $evaluationId
     *
     * @return Note
     */
    public function setEvaluationId(\EcoleBundle\Entity\Evaluation $evaluationId = null)
    {
        $this->evaluation = $evaluationId;

        return $this;
    }

    /**
     * Get evaluationId.
     *
     * @return \EcoleBundle\Entity\Evaluation
     */
    public function getEvaluationId()
    {
        return $this->evaluation;
    }

    /**
     * Set etudiantId.
     *
     * @param \EcoleBundle\Entity\Etudiant $etudiantId
     *
     * @return Note
     */
    public function setEtudiantId(\EcoleBundle\Entity\Etudiant $etudiantId = null)
    {
        $this->etudiant = $etudiantId;

        return $this;
    }

    /**
     * Get etudiantId.
     *
     * @return \EcoleBundle\Entity\Etudiant
     */
    public function getEtudiantId()
    {
        return $this->etudiant;
    }

    /**
     * Set evaluation.
     *
     * @param \EcoleBundle\Entity\Evaluation $evaluation
     *
     * @return Note
     */
    public function setEvaluation(\EcoleBundle\Entity\Evaluation $evaluation = null)
    {
        $this->evaluation = $evaluation;

        return $this;
    }

    /**
     * Get evaluation.
     *
     * @return \EcoleBundle\Entity\Evaluation
     */
    public function getEvaluation()
    {
        return $this->evaluation;
    }

    /**
     * Set classe.
     *
     * @param \EcoleBundle\Entity\Classe $classe
     *
     * @return Note
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
}
