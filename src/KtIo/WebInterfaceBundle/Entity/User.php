<?php

namespace KtIo\WebInterfaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KtIo\WebInterfaceBundle\Entity\UserRepository")
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=23)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_visit", type="datetime")
     */
    private $lastVisit;
    
    /**
     * @ORM\OneToMany(targetEntity="Url", mappedBy="user")
     */
    private $urls;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
    
    /**
     * Creates a token
     *
     * @return User
     */
    public function createToken()
    {
        $this->token = uniqid('', true);
        
        return $this;
    }

    /**
     * Update lastVisit to the current time
     *
     * @param \DateTime $lastVisit
     * @return User
     */
    public function updateLastVisit()
    {
        $this->lastVisit = new \DateTime();

        return $this;
    }

    /**
     * Get lastVisit
     *
     * @return \DateTime
     */
    public function getLastVisit()
    {
        return $this->lastVisit;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->urls = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add urls
     *
     * @param \KtIo\WebInterfaceBundle\Entity\Url $urls
     * @return User
     */
    public function addUrl(\KtIo\WebInterfaceBundle\Entity\Url $urls)
    {
        $this->urls[] = $urls;

        return $this;
    }

    /**
     * Remove urls
     *
     * @param \KtIo\WebInterfaceBundle\Entity\Url $urls
     */
    public function removeUrl(\KtIo\WebInterfaceBundle\Entity\Url $urls)
    {
        $this->urls->removeElement($urls);
    }

    /**
     * Get urls
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUrls()
    {
        return $this->urls;
    }
}
