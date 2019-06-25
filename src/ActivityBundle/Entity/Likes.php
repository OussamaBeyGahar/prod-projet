<?php

namespace ActivityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Likes
 *
 * @ORM\Table(name="likes")
 * @ORM\Entity(repositoryClass="ActivityBundle\Repository\LikesRepository")
 */
class Likes
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
    private $inUser;


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
     * @return Likes
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
     * Set inUser
     *
     * @param integer $inUser
     *
     * @return Likes
     */
    public function setInUser($inUser)
    {
        $this->inUser = $inUser;

        return $this;
    }

    /**
     * Get inUser
     *
     * @return int
     */
    public function getInUser()
    {
        return $this->inUser;
    }
}

