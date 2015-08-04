<?php

/*
 * This file is part of sonata-project.
 *
 * (c) 2010 Thomas Rabaix
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nz\PortfolioBundle\Twig\Extension;

use Nz\PortfolioBundle\Model\WorkInterface;
use Nz\PortfolioBundle\Permalink\PermalinkInterface;

class PortfolioExtension extends \Twig_Extension
{
    /**
     * @var Nz\PortfolioBundle\Permalink\PermalinkInterface
     */
    private $permalinkGenerator;

    /**
     * @param Nz\PortfolioBundle\Permalink\PermalinkInterface  $permalinkGenerator
     */
    public function __construct(PermalinkInterface $permalinkGenerator)
    {
        $this->permalinkGenerator = $permalinkGenerator;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'nz_portfolio_permalink' => new \Twig_Function_Method($this, 'generatePermalink')
        );
    }
    /**
     * {@inheritdoc}
      public function initRuntime(\Twig_Environment $environment)
      {
      $this->environment = $environment;
      }
     */

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'nz_portfolio';
    }

    /**
     * @param \Sonata\NewsBundle\Model\PostInterface $post
     *
     * @return string|Exception
     */
    public function generatePermalink(WorkInterface $work)
    {
        return $this->permalinkGenerator->generate($work);
    }
}
