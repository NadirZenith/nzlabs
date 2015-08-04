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

use Knp\Menu\FactoryInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
/*use Sonata\NewsBundle\Model\BlogInterface;*/
use Nz\PortfolioBundle\Permalink\PermalinkInterface;

/**
 * BlockService for post breadcrumb
 *
 * @author Sylvain Deloux <sylvain.deloux@ekino.com>
 */
class PortfolioWorkBreadcrumbBlockService extends BasePortfolioBreadcrumbBlockService
{
    /**
     * @var BlogInterface
     */
    protected $permalinkGenerator;

    public function __construct($context, $name, EngineInterface $templating, MenuProviderInterface $menuProvider, FactoryInterface $factory, PermalinkInterface $permalinkGenerator)
    {
        $this->permalinkGenerator = $permalinkGenerator;

        parent::__construct($context, $name, $templating, $menuProvider, $factory);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'nz.portfolio.block.breadcrumb_work';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMenu(BlockContextInterface $blockContext)
    {
        $menu = $this->getRootMenu($blockContext);

        if ($work = $blockContext->getBlock()->getSetting('work')) {
            $menu->addChild($work->getTitle(), array(
                'route'           => 'nz_portfolio_view',
                'routeParameters' => array(
                    'permalink' => $this->permalinkGenerator->generate($work),
                ),
            ));
        }

        return $menu;
    }
}
