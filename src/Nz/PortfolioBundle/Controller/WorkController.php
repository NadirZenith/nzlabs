<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nz\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
/* use Sonata\NewsBundle\Model\CommentInterface; */
use Nz\PortfolioBundle\Model\WorkInterface;

class WorkController extends Controller
{

    /**
     * @return RedirectResponse
     */
    public function homeAction()
    {
        return $this->redirect($this->generateUrl('nz_portfolio_archive'));
    }

    /**
     * @return Response
     */
    public function archiveAction()
    {
        return $this->renderArchive();
    }

    /**
     * @param array $criteria
     * @param array $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderArchive(array $criteria = array(), array $parameters = array())
    {


        $pager = $this->getWorkManager()->getPager(
            $criteria, $this->getRequest()->get('page', 1)
        );

        $parameters = array_merge(array(
            'pager' => $pager,
            /* 'blog' => $this->get('sonata.news.blog'), */
            'tag' => false,
            'collection' => false,
            'route' => $this->getRequest()->get('_route'),
            'route_parameters' => $this->getRequest()->get('_route_params')
            ), $parameters);

        /* d($parameters); */
        /* return new \Symfony\Component\HttpFoundation\Response('nice'); */
/*
        if ($seoPage = $this->getSeoPage()) {
            $seoPage
                ->setTitle('title')
                ->addMeta('name', 'description', 'archive description')
                ->addMeta('property', 'og:title', 'title')
                ->addMeta('property', 'og:type', 'blog')
                ->addMeta('property', 'og:url', $this->generateUrl('sonata_news_archive'))
                ->addMeta('property', 'og:description', 'description')
            ;
        }
 */

        $response = $this->render(sprintf('NzPortfolioBundle:Work:archive.%s.twig', $this->getRequest()->getRequestFormat()), $parameters);

        if ('rss' === $this->getRequest()->getRequestFormat()) {
            $response->headers->set('Content-Type', 'application/rss+xml');
        }

        return $response;
    }

    /**
     * @throws NotFoundHttpException
     *
     * @param $permalink
     *
     * @return Response
     */
    public function viewAction($permalink)
    {
        $permalinkGenerator = $this->container->get('nz.portfolio.permalink.generator');
        $work = $this->getWorkManager()->findOneByPermalink($permalink, $permalinkGenerator);

        if (!$work || !$work->isPublic()) {
            throw new NotFoundHttpException('Unable to find the work');
        }

        if ($seoPage = $this->getSeoPage()) {
            $seoPage
                ->setTitle($work->getTitle())
                ->addMeta('name', 'description', $work->getAbstract())
                ->addMeta('property', 'og:title', $work->getTitle())
                ->addMeta('property', 'og:type', 'blog')
                ->addMeta('property', 'og:url', $this->generateUrl('nz_portfolio_view', array(
                        'permalink' => $permalinkGenerator->generate($work)
                        ), true))
                ->addMeta('property', 'og:description', $work->getAbstract())
            ;
        }

        return $this->render('NzPortfolioBundle:Work:view.html.twig', array(
                'work' => $work,
                'form' => false,
        ));
    }

    /**
     * @return \Sonata\SeoBundle\Seo\SeoPageInterface
     */
    public function getSeoPage()
    {
        if ($this->has('sonata.seo.page')) {
            return $this->get('sonata.seo.page');
        }

        return null;
    }

    /**
     * @return \Nz\PortfolioBundle\Model\WorkManagerInterface
     */
    protected function getWorkManager()
    {
        return $this->get('nz.portfolio.manager.work');
    }
}
