<?php

namespace KtIo\WebInterfaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Url
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KtIo\WebInterfaceBundle\Entity\UrlRepository")
 */
class Url
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
     * @ORM\Column(name="short_url", type="string", length=6)
     */
    private $shortUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="target_url", type="string", length=320)
     */
    private $targetUrl;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="urls")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


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
     * Set shortUrl
     *
     * @param string $shortUrl
     * @return Url
     */
    public function setShortUrl($shortUrl)
    {
        $this->shortUrl = $shortUrl;

        return $this;
    }

    /**
     * Get shortUrl
     *
     * @return string
     */
    public function getShortUrl()
    {
        return $this->shortUrl;
    }

    /**
     * Set targetUrl
     *
     * @param string $targetUrl
     * @return Url
     */
    public function setTargetUrl($targetUrl)
    {
        $this->targetUrl = $targetUrl;

        return $this;
    }

    /**
     * Get targetUrl
     *
     * @return string
     */
    public function getTargetUrl()
    {
        return $this->targetUrl;
    }

    /**
     * Set user
     *
     * @param \KtIo\WebInterfaceBundle\Entity\User $user
     * @return Url
     */
    public function setUser(\KtIo\WebInterfaceBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \KtIo\WebInterfaceBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
