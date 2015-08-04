<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nz\PortfolioBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;

/**
 * SonataNewsBundleExtension
 *
 * @author      Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class NzPortfolioExtension extends Extension
{

    /**
     * @throws \InvalidArgumentException
     *
     * @param array                                                   $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        
        $loader->load('services.xml');
        $loader->load('orm.xml');
        $loader->load('admin.xml');
        $loader->load('twig.xml');
        $loader->load('block.xml');
        
        $container->setAlias('nz.portfolio.permalink.generator', $config['permalink_generator']);


        $this->registerDoctrineMapping($config, $container);
        $this->configureClass($config, $container);
        $this->configureAdmin($config, $container);
    }

    public function load2(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);
        $bundles = $container->getParameter('kernel.bundles');

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('orm.xml');
        $loader->load('twig.xml');
        $loader->load('form.xml');
        $loader->load('core.xml');
        $loader->load('block.xml');
        $loader->load('serializer.xml');

        if (isset($bundles['FOSRestBundle']) && isset($bundles['NelmioApiDocBundle'])) {
            $loader->load('api_controllers.xml');
            $loader->load('api_form.xml');
        }

        if (isset($bundles['SonataAdminBundle'])) {
            $loader->load('admin.xml');
        }

        if (!isset($config['salt'])) {
            throw new \InvalidArgumentException("The configuration node 'salt' is not set for the SonataNewsBundle (sonata_news)");
        }

        if (!isset($config['comment'])) {
            throw new \InvalidArgumentException("The configuration node 'comment' is not set for the SonataNewsBundle (sonata_news)");
        }

        $container->getDefinition('sonata.news.hash.generator')
            ->replaceArgument(0, $config['salt']);

        $container->getDefinition('sonata.news.permalink.date')
            ->replaceArgument(0, $config['permalink']['date']);

        $container->setAlias('sonata.news.permalink.generator', $config['permalink_generator']);

        $container->setDefinition('sonata.news.blog', new Definition('Sonata\NewsBundle\Model\Blog', array(
            $config['title'],
            $config['link'],
            $config['description'],
            new Reference('sonata.news.permalink.generator')
        )));

        $container->getDefinition('sonata.news.hash.generator')
            ->replaceArgument(0, $config['salt']);

        $container->getDefinition('sonata.news.mailer')
            ->replaceArgument(5, array(
                'notification' => $config['comment']['notification']
        ));

        $this->registerDoctrineMapping($config, $container);
        $this->configureClass($config, $container);
        $this->configureAdmin($config, $container);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function configureClass($config, ContainerBuilder $container)
    {
        // admin configuration
        $container->setParameter('nz.portfolio.admin.work.entity', $config['class']['work']);
        
        //$container->setParameter('sonata.news.admin.post.entity', $config['class']['post']);
        //$container->setParameter('sonata.news.admin.comment.entity', $config['class']['comment']);

        // manager configuration
        $container->setParameter('nz.portfolio.manager.work.entity', $config['class']['work']);
        
        //$container->setParameter('sonata.news.manager.post.entity', $config['class']['post']);
        //$container->setParameter('sonata.news.manager.comment.entity', $config['class']['comment']);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function configureAdmin($config, ContainerBuilder $container)
    {
        $container->setParameter('nz.portfolio.admin.work.class', $config['admin']['work']['class']);
        $container->setParameter('nz.portfolio.admin.work.controller', $config['admin']['work']['controller']);
        $container->setParameter('nz.portfolio.admin.work.translation_domain', $config['admin']['work']['translation']);

        /*
          $container->setParameter('sonata.news.admin.comment.class', $config['admin']['comment']['class']);
          $container->setParameter('sonata.news.admin.comment.controller', $config['admin']['comment']['controller']);
          $container->setParameter('sonata.news.admin.comment.translation_domain', $config['admin']['comment']['translation']);
         */
    }

    /**
     * @param array $config
     */
    public function registerDoctrineMapping(array $config)
    {

        $collector = DoctrineCollector::getInstance();

        foreach ($config['class'] as $type => $class) {
            if (!class_exists($class)) {
                return;
            }
        }
        /* var_dump($config); */
        /*
          $collector->addAssociation($config['class']['post'], 'mapOneToMany', array(
          'fieldName' => 'comments',
          'targetEntity' => $config['class']['comment'],
          'cascade' =>
          array(
          0 => 'remove',
          1 => 'persist',
          ),
          'mappedBy' => 'post',
          'orphanRemoval' => true,
          'orderBy' =>
          array(
          'createdAt' => 'DESC',
          ),
          ));
         */

        $collector->addAssociation($config['class']['work'], 'mapManyToOne', array(
            'fieldName' => 'image',
            'targetEntity' => $config['class']['media'],
            'cascade' =>
            array(
                0 => 'remove',
                1 => 'persist',
                2 => 'refresh',
                3 => 'merge',
                4 => 'detach',
            ),
            'mappedBy' => NULL,
            'inversedBy' => NULL,
            'joinColumns' =>
            array(
                array(
                    'name' => 'image_id',
                    'referencedColumnName' => 'id',
                ),
            ),
            'orphanRemoval' => false,
        ));

        $collector->addAssociation($config['class']['work'], 'mapManyToOne', array(
            'fieldName' => 'author',
            'targetEntity' => $config['class']['user'],
            'cascade' =>
            array(
                1 => 'persist',
            ),
            'mappedBy' => NULL,
            'inversedBy' => NULL,
            'joinColumns' =>
            array(
                array(
                    'name' => 'author_id',
                    'referencedColumnName' => 'id',
                ),
            ),
            'orphanRemoval' => false,
        ));

        $collector->addAssociation($config['class']['work'], 'mapManyToOne', array(
            'fieldName' => 'collection',
            'targetEntity' => $config['class']['collection'],
            'cascade' =>
            array(
                1 => 'persist',
            ),
            'mappedBy' => NULL,
            'inversedBy' => NULL,
            'joinColumns' =>
            array(
                array(
                    'name' => 'collection_id',
                    'referencedColumnName' => 'id',
                ),
            ),
            'orphanRemoval' => false,
        ));

        $collector->addAssociation($config['class']['work'], 'mapManyToMany', array(
            'fieldName' => 'tags',
            'targetEntity' => $config['class']['tag'],
            'cascade' =>
            array(
                1 => 'persist',
            ),
            'joinTable' =>
            array(
                'name' => 'portfolio__work_tag',
                'joinColumns' =>
                array(
                    array(
                        'name' => 'post_id',
                        'referencedColumnName' => 'id',
                    ),
                ),
                'inverseJoinColumns' =>
                array(
                    array(
                        'name' => 'tag_id',
                        'referencedColumnName' => 'id',
                    ),
                ),
            ),
        ));
        /*
          $collector->addAssociation($config['class']['comment'], 'mapManyToOne', array(
          'fieldName' => 'post',
          'targetEntity' => $config['class']['post'],
          'cascade' => array(
          ),
          'mappedBy' => NULL,
          'inversedBy' => 'comments',
          'joinColumns' =>
          array(
          array(
          'name' => 'post_id',
          'referencedColumnName' => 'id',
          'nullable' => false
          ),
          ),
          'orphanRemoval' => false,
          ));
         */
    }
}