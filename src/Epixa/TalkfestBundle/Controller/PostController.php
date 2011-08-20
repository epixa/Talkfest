<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Symfony\Component\HttpFoundation\Request,
    Epixa\TalkfestBundle\Entity\Post,
    Epixa\TalkfestBundle\Form\Type\PostType;

/**
 * Controller managing posts
 * 
 * @category   Talkfest
 * @package    Controller
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class PostController extends Controller
{
    /**
     * Shows a specific post including paginated associated comments
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="view_post")
     * @Template()
     *
     * @param integer $id   The unique identifier of the requested post
     * @return array
     */
    public function viewAction($id)
    {
        $post = $this->getPostService()->get($id);

        return array(
            'post' => $post,
            'comments' => $this->getCommentService()->getByPost($post)
        );
    }

    /**
     * @Route("/add/{categoryId}", requirements={"categoryId"="\d+"}, name="add_post")
     * @Template()
     *
     * @param integer $categoryId
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function addAction($categoryId, Request $request)
    {
        $category = $this->getCategoryService()->get($categoryId);
        
        $post = new Post($category);

        $form = $this->createForm(new PostType(), $post);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $this->getPostService()->add($post);

                $this->get('session')->setFlash('notice', 'Post created');
                return $this->redirect($this->generateUrl('view_post', array('id' => $post->getId())));
            }
        }

        return array(
            'form' => $form->createView(),
            'category' => $category
        );
    }

    /**
     * @Route("/edit/{id}", requirements={"id"="\d+"}, name="edit_post")
     * @Template()
     *
     * @param integer $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function editAction($id, Request $request)
    {
        $service = $this->getPostService();
        $post = $service->get($id);

        $form = $this->createForm(new PostType(), $post);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $service->update($post);

                $this->get('session')->setFlash('notice', 'Post updated');
                return $this->redirect($this->generateUrl('view_post', array('id' => $post->getId())));
            }
        }

        return array(
            'form' => $form->createView(),
            'post' => $post
        );
    }

    /**
     * @Route("/delete/{id}", requirements={"id"="\d+"}, name="delete_post")
     * @Template()
     *
     * @param integer $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function deleteAction($id, Request $request)
    {
        $service = $this->getPostService();
        $post = $service->get($id);

        if ($request->getMethod() == 'POST') {
            $category = $post->getCategory();
            $service->delete($post);

            $this->get('session')->setFlash('notice', 'Post deleted');
            return $this->redirect($this->generateUrl('view_category', array('id' => $category->getId())));
        }

        return array(
            'post' => $post
        );
    }

    /**
     * Gets the category service
     *
     * @return \Epixa\TalkfestBundle\Service\CategoryService
     */
    public function getCategoryService()
    {
        return $this->get('talkfest.service.category');
    }

    /**
     * Gets the post service
     *
     * @return \Epixa\TalkfestBundle\Service\PostService
     */
    public function getPostService()
    {
        return $this->get('talkfest.service.post');
    }

    /**
     * Gets the comment service
     *
     * @return \Epixa\TalkfestBundle\Service\CommentService
     */
    public function getCommentService()
    {
        return $this->get('talkfest.service.comment');
    }
}
