<?php

namespace EcoleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Absence.
 *
 * @ORM\Table(name="absence")
 * @ORM\Entity()
 */
class Absence
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
     * @var int
     *
     * @ORM\Column(name="nombre", type="integer")
     */
    private $nombre;
    /**
     * @ORM\JoinColumn( onDelete="SET NULL")
     * @ORM\ManyToOne(targetEntity="EcoleBundle\Entity\Etudiant", inversedBy="ab")
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
     * Set nombre.
     *
     * @param int $nombre
     *
     * @return Absence
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return int
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set etudiant.
     *
     * @param \EcoleBundle\Entity\Etudiant $etudiant
     *
     * @return Absence
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
}
