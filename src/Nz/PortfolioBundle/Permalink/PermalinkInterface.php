<?php

namespace Nz\PortfolioBundle\Permalink;

use Nz\PortfolioBundle\Model\WorkInterface;

interface PermalinkInterface
{

    /**
     * @param \Nz\PortfolioBundle\Model\WorkInterface $work
     */
    public function generate(WorkInterface $work);

    /**
     * @param string $permalink
     *
     * @return array
     */
    public function getParameters($permalink);
}
