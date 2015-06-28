<?php
namespace AppBundle\Block;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sonata\BlockBundle\Block\BlockServiceManager as BaseBlockServiceManager;
use Sonata\BlockBundle\Block\BlockServiceManagerInterface;

class BlockServiceManager extends BaseBlockServiceManager implements BlockServiceManagerInterface
{

    /**
     * {@inheritdoc}
     */
    public function getServicesByContext($context, $includeContainers = true)
    {
        /*d(__FILE__);*/
        if (!array_key_exists($context, $this->contexts)) {
            return array();
        }

        $services = array();

        $containers = $this->container->getParameter('sonata.block.container.types');

        $allow ="sonata.page.block.container";

        if(($key = array_search($allow, $containers)) !== false) {
            unset($containers[$key]);
        }

        foreach ($this->contexts[$context] as $name) {
            if (!$includeContainers && in_array($name, $containers)) {
                continue;
            }

            $services[$name] = $this->getService($name);
        }

        return $services;
    }

}