<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerAware;


class MenuBuilder extends ContainerAware
{
   

    public function createMainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        /*$menu->addChild('Hello', array('route' => 'app_hello', 'routeParameters' => array('name' => 'tino')));*/
        $menu->addChild('Blog', array('route' => 'sonata_news_archive'));
        // ... add more children

        return $menu;
    }
}
