<?php

namespace Nz\PortfolioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Proxy\Proxy;
use Symfony\Component\Validator\Constraints as Constraints;
use Sonata\MediaBundle\Model\MediaInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Portfolio
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="title", type="text", nullable=false)
     * @Constraints\NotBlank()
     */
    protected $title;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     * @Constraints\NotBlank()
     */
    protected $content;

    /**
     * {@inheritdoc}
     *
     * @ORM\Column(name="status", type="string", length=20, nullable=false)
     */
    protected $status = "publish";

    public function __construct()
    {
        
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        
    }
}
