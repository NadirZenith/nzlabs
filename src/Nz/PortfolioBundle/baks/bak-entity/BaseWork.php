<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nz\PortfolioBundle\Entity;

use Nz\PortfolioBundle\Model\Work as ModelWork;
use Doctrine\Common\Collections\ArrayCollection;

abstract class BaseWork extends ModelWork
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this->tags     = new ArrayCollection();
        /*$this->comments = new ArrayCollection();*/

        $this->setPublicationDateStart(new \DateTime);
    }
}
