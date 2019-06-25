<?php

namespace ActivityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reports
 *
 * @ORM\Table(name="reports")
 * @ORM\Entity(repositoryClass="ActivityBundle\Repository\ReportsRepository")
 */
class Reports
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
     * @ORM\ManyToOne(targetEntity="Activity")
     * @ORM\JoinColumn(name="idAct", referencedColumnName="id", onDelete="CASCADE")
     */
    private $idAct;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="idUser", referencedColumnName="id")
     */
    private $idUser;


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
     * Set idAct
     *
     * @param integer $idAct
     *
     * @return Reports
     */
    public function setIdAct($idAct)
    {
        $this->idAct = $idAct;

        return $this;
    }

    /**
     * Get idAct
     *
     * @return int
     */
    public function getIdAct()
    {
        return $this->idAct;
    }

    /**
     * Set idUser
     *
     * @param integer $idUser
     *
     * @return Reports
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return int
     */
    public function getIdUser()
    {
        return $this->idUser;
    }
}

