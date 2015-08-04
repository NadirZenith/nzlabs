<?php

namespace Nz\PortfolioBundle\Permalink;

use Nz\PortfolioBundle\Model\WorkInterface;

class SlugPermalink implements PermalinkInterface
{

    /**
     * {@inheritdoc}
     */
    public function generate(WorkInterface $work)
    {
        return $work->getSlug();
        
        return null == $work->getCollection() ? //
            $work->getSlug() : sprintf('%s/%s', $work->getCollection()->getSlug(), $work->getSlug());
    }

    /**
     * @param string $permalink
     *
     * @return array
     */
    public function getParameters($permalink)
    {
        $parameters = explode('/', $permalink);

        if (count($parameters) > 2 || count($parameters) == 0) {
            throw new \InvalidArgumentException('wrong permalink format');
        }

        if (false === strpos($permalink, '/')) {
            $collection = null;
            $slug = $permalink;
        } else {
            list($collection, $slug) = $parameters;
        }

        return array(
            'collection' => $collection,
            'slug' => $slug,
        );
    }
}
