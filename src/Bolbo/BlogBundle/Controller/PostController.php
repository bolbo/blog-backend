<?php

namespace Bolbo\BlogBundle\Controller;

use Bolbo\BlogBundle\Form\PostType;
use Bolbo\Component\Model\Database\PublicSchema\Post;
use Bolbo\Component\Model\Database\PublicSchema\PostModel;
use Bolbo\BlogBundle\Model\PostCollection;

use FOS\RestBundle\Util\Codes;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\RouteRedirectView;

use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use PommProject\Foundation\Test\Unit\Pomm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Rest controller for posts
 *
 * @package Bolbo\BlogBundle\Controller
 * @author  Bolbo
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
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing posts.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many posts to return.")
     * @Annotations\QueryParam(name="filter", default="", description="Filters.")
     * @Annotations\QueryParam(name="sort", default="", description="Sort.")
     *
     * @Annotations\View()
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getPostsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        //dump($paramFetcher);exit;

        $offset = $paramFetcher->get('offset');
        $start = null == $offset ? 0 : $offset + 1;
        $limit = $paramFetcher->get('limit');
        $filter = json_decode($paramFetcher->get('filter'));
        $sort = json_decode($paramFetcher->get('sort'));

        $posts = $this->getPostManager()->fetch($start, $limit, $filter, $sort);

        return new PostCollection($posts, $offset, $limit);
    }


    /**
     * Get a single post.
     *
     * @ApiDoc(
     *   output = "Bolbo\BlobBundle\Model\Post",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the post is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="post")
     *
     * @param Request $request the request object
     * @param int     $id      the post id
     *
     * @return array
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function getPostAction(Request $request, $id)
    {
        $post = $this->getPostManager()->get($id);
        if (false === $post) {
            throw $this->createNotFoundException("Post does not exist.");
        }

        $view = new View($post);
        $group = $this->container->get('security.authorization_checker')->isGranted('ROLE_API')
            ? 'restapi'
            : 'standard';
        $view->getSerializationContext()->setGroups(array('Default', $group));

        return $view;
    }


    /**
     * Presents the form to use to create a new post.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @return FormTypeInterface
     */
    public function newPostAction()
    {
        return $this->createForm(new PostType());
    }


    /**
     * Creates a new post from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Bolbo\BlogBundle\Form\PostType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template = "BolboBlogBundle:Post:newPost.html.twig",
     *   statusCode = Codes::HTTP_BAD_REQUEST
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|RouteRedirectView
     */
    public function postPostsAction(Request $request)
    {
        $post = new Post(
            ['title'            => null,
             'content'          => null,
             'category_id'      => 1,
             'slug'             => null,
             'author_id'        => 1,
             'meta_title'       => null,
             'meta_description' => null,
             'meta_keyword'     => null,
             'tag'              => null,
            ]);

        $form = $this->createForm(new PostType(), $post);
        $form->submit($request);
        if ($form->isValid()) {
            //$this->getPostManager()->getPommModel()->insertOne($post);
            $result = $this->getPostManager()->save($post);

            return $this->routeRedirectView('get_post', array('id' => $result->getId()));
        }

        return array(
            'form' => $form
        );
    }


    /**
     * Presents the form to use to update an existing post.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     200 = "Returned when successful",
     *     404 = "Returned when the post is not found"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @param Request $request the request object
     * @param int     $id      the post id
     *
     * @return FormTypeInterface
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function editPostsAction(Request $request, $id)
    {
        $post = $this->getPostManager()->get($id);
        if (false === $post) {
            throw $this->createNotFoundException("Post does not exist.");
        }

        $form = $this->createForm(new PostType(), $post);

        return $form;
    }


    /**
     * Update existing post from the submitted data or create a new post at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Bolbo\BlogBundle\Form\PostType",
     *   statusCodes = {
     *     201 = "Returned when a new resource is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template="BolboBlogBundle:Post:editPost.html.twig",
     *   templateVar="form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the post id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function putPostsAction(Request $request, $id)
    {
        $post = $this->getPostManager()->get($id);
        if (false === $post) {
            $post = new Post();
            $post->id = $id;
            $statusCode = Codes::HTTP_CREATED;
        } else {
            $statusCode = Codes::HTTP_NO_CONTENT;
        }

        $form = $this->createForm(new PostType(), $post);

        $form->submit($request);
        if ($form->isValid()) {
            $result = $this->getPostManager()->save($post);
            dump($result);
            exit;

            return $this->routeRedirectView('get_post', array('id' => $post->id), $statusCode);
        }

        return $form;
    }


    /**
     * Removes a post.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the post id
     *
     * @return RouteRedirectView
     */
    public function deletePostsAction(Request $request, $id)
    {
        $this->getPostManager()->delete($id);

        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView('get_posts', array(), Codes::HTTP_NO_CONTENT);
    }


    /**
     * Removes a post.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the post id
     *
     * @return RouteRedirectView
     */
    public function removePostsAction(Request $request, $id)
    {
        return $this->deletePostsAction($request, $id);
    }
}
