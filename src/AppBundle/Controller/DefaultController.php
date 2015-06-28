<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    private $mem;

    public function __construct()
    {
        $this->mem = new \Memcached();
        $this->mem->addServer("127.0.0.1", 11211);
    }

    public function indexAction($name)
    {
        return $this->render('AppBundle:Default:index.html.twig', array('name' => $name));
    }

    /**
     * @Route("/getit", name="getit")
     */
    public function getitAction()
    {
        $em = $this->getDoctrine()->getManager();
        $author = $this->get('security.context')->getToken()->getUser();

        $mem_posts = $this->mem->get('posts');
        d($mem_posts);
        
         return new \Symfony\Component\HttpFoundation\Response('<html><head></head><body>getit</body></html>'); 

        foreach ($mem_posts as $i => $p) {
            $post = new \Application\Sonata\NewsBundle\Entity\Post();

            $post->setAuthor($author);
            $post->setContentFormatter('richhtml');
            $post->setEnabled(TRUE);
            $post->setCommentsDefaultStatus(1);
            $post->setCreatedAt($p['createdAt']);
            $post->setPublicationDateStart($p['createdAt']);
            $post->setUpdatedAt($p['updatedAt']);
            $post->setTitle($p['title']);
            $post->setContent($p['content']);
            $post->setRawContent($p['content']);

            $post->setAbstract($this->limit_text(strip_tags($p['content']), 15));
            $post->setSlug($p['slug']);

            foreach ($p['tags'] as $tag_name) {
                $post->addTags($this->mergeTag($tag_name));
            }

            $em->getClassMetadata(get_class($post))->setLifecycleCallbacks(array());
            $em->persist($post);

            /* d($i); */
            /*d($post);*/
/*            
            if ($i == 1) {
                break;
            }
 */
             $em->flush(); 
        }



        /*
          $post = $this->getDoctrine()
          ->getRepository('ApplicationSonataNewsBundle:Post')
          ->find(1);

          d($post);
         */



        return new \Symfony\Component\HttpFoundation\Response('<html><head></head><body>getit</body></html>');
    }

    public function limit_text($text, $limit)
    {
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$limit]) . '...';
        }
        return $text;
    }

    public function mergeTag($tag_name)
    {

        $tag = $this->getDoctrine()
            ->getRepository('ApplicationSonataClassificationBundle:Tag')
            ->findOneBy(['name' => $tag_name]);


        if (!$tag) {
            $tag = new \Application\Sonata\ClassificationBundle\Entity\Tag();
            $tag->setEnabled(true);
            $tag->setName($tag_name);

            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();
        }

        return $tag;
    }
}
