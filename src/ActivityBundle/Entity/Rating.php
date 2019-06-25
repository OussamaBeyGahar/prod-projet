<?php

namespace ActivityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rating
 *
 * @ORM\Table(name="rating")
 * @ORM\Entity(repositoryClass="ActivityBundle\Repository\RatingRepository")
 */
class Rating
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
     * @ORM\Column(name="security", type="integer")
     */
    private $security;

    /**
     * @var int
     *
     * @ORM\Column(name="beauty", type="integer")
     */
    private $beauty;

    /**
     * @var int
     *
     * @ORM\Column(name="budget", type="integer")
     */
    private $budget;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Activity")
     * @ORM\JoinColumn(name="idact", referencedColumnName="id", onDelete="CASCADE")
     */
    private $idact;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     */
    private $iduser;


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
     * Set security
     *
     * @param integer $security
     *
     * @return Rating
     */
    public function setSecurity($security)
    {
        $this->security = $security;

        return $this;
    }

    /**
     * Get security
     *
     * @return int
     */
    public function getSecurity()
    {
        return $this->security;
    }

    /**
     * Set beauty
     *
     * @param integer $beauty
     *
     * @return Rating
     */
    public function setBeauty($beauty)
    {
        $this->beauty = $beauty;

        return $this;
    }

    /**
     * Get beauty
     *
     * @return int
     */
    public function getBeauty()
    {
        return $this->beauty;
    }

    /**
     * Set budget
     *
     * @param integer $budget
     *
     * @return Rating
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return int
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set idact
     *
     * @param integer $idact
     *
     * @return Rating
     */
    public function setIdact($idact)
    {
        $this->idact = $idact;

        return $this;
    }

    /**
     * Get idact
     *
     * @return int
     */
    public function getIdact()
    {
        return $this->idact;
    }

    /**
     * Set iduser
     *
     * @param integer $iduser
     *
     * @return Rating
     */
    public function setIduser($iduser)
    {
        $this->iduser = $iduser;

        return $this;
    }

    /**
     * Get iduser
     *
     * @return int
     */
    public function getIduser()
    {
        return $this->iduser;
    }
}

