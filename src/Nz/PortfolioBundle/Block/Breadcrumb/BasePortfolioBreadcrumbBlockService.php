<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nz\PortfolioBundle\Block\Breadcrumb;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\SeoBundle\Block\Breadcrumb\BaseBreadcrumbMenuBlockService;

/**
 * Abstract class for news breadcrumbs
 *
 * @author Sylvain Deloux <sylvain.deloux@ekino.com>
 */
abstract class BasePortfolioBreadcrumbBlockService extends BaseBreadcrumbMenuBlockService
{
    /**
     * {@inheritdoc}
     */
    protected function getRootMenu(BlockContextInterface $blockContext)
    {
        $menu = parent::getRootMenu($blockContext);

        $menu->addChild('nz_portfolio_archive_breadcrumb', array(
            'route'  => 'nz_portfolio_home',
            'extras' => array('translation_domain' => 'NzPortfolioBundle')
        ));

        return $menu;
    }
}
