<?php

namespace KtIo\WebInterfaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use KtIo\WebInterfaceBundle\Entity\User;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Url
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="KtIo\WebInterfaceBundle\Entity\UrlRepository")
 * @ExclusionPolicy("all")
 */
class Url
{
    /**
     * Primary key, unique identifier.
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * The hash that we should match against in order to retrieve this entity.
     * @var string
     *
     * @ORM\Column(name="short_url", type="string", length=8, nullable=true)
     * @Expose
     */
    private $shortUrl;

    /**
     * The URL that we should redirect to.
     * @var string
     *
     * @ORM\Column(name="target_url", type="string", length=320)
     * @Expose
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
     * @param User $user
     * @return Url
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
