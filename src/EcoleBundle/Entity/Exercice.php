<?php

namespace EcoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exercice.
 *
 * @ORM\Table(name="exercice")
 * @ORM\Entity()
 */
class Exercice
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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    /**
     * @ORM\JoinColumn( onDelete="SET NULL")
     * @ORM\ManyToOne(targetEntity="EcoleBundle\Entity\Classe", inversedBy="exercice")
     */
    private $classe;

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
     * Set description.
     *
     * @param string $description
     *
     * @return Exercice
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
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Exercice
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
     * @param \EcoleBundle\Entity\Exercice $classe
     */
    public function setClasse(\EcoleBundle\Entity\Classe $classe = null)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe.
     *
     * @return \EcoleBundle\Entity\Exercice
     */
    public function getClasse()
    {
        return $this->classe;
    }
}
