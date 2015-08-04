<?php

namespace Nz\PortfolioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Proxy\Proxy;
use Symfony\Component\Validator\Constraints as Constraints;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\ClassificationBundle\Model\Tag;
use Nz\PortfolioBundle\Model\Work as ModelWork;

abstract class BaseWork extends ModelWork
{

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this->tags = new ArrayCollection();
        /*$this->comments = new ArrayCollection();*/

        $this->setPublicationDateStart(new \DateTime);
    }
}
