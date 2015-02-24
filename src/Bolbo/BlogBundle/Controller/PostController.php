<?php

namespace Bolbo\BlogBundle\Controller;

use Bolbo\BlogBundle\Form\PostType;
use Bolbo\BlogBundle\Model\Post;
use Bolbo\BlogBundle\Model\PostCollection;

use FOS\RestBundle\Util\Codes;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\RouteRedirectView;

use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Rest controller for notes
 *
 * @package Bolbo\BlogBundle\Controller
 * @author Bolbo
 */
class PostController extends FOSRestController
{
    /**
     * return \Bolbo\BlogBundle\PostManager
     */
    public function getPostManager()
    {
        return $this->get('bolbo.blog.post_manager');
    }

    /**
     * List all posts.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing notes.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many notes to return.")
     *
     * @Annotations\View()
     *
     * @param Request $request the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getPostsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $start = null == $offset ? 0 : $offset + 1;
        $limit = $paramFetcher->get('limit');

        $notes = $this->getPostManager()->fetch($start, $limit);

        return new JsonResponse(['test']);
        //return new PostCollection($notes, $offset, $limit);
    }

}
