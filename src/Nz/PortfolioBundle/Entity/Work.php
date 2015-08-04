<?php

namespace Nz\PortfolioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Proxy\Proxy;
use Symfony\Component\Validator\Constraints as Constraints;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\ClassificationBundle\Model\Tag;
use Nz\PortfolioBundle\Entity\BaseWork;

class Work extends BaseWork
{
    protected $id;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }
}
