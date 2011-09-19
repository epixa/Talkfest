<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Symfony\Component\HttpFoundation\Request,
    JMS\SecurityExtraBundle\Annotation\Secure,
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
     * Shows a list of all posts
     *
     * @Route("/", name="post_index")
     * @Route("/{page}", requirements={"id"="\d+", "page"="\d+"}, name="post_index_page")
     * @Template()
     *
     * @param integer $page
     * @return array
     */
    public function indexAction($page = 1)
    {
        return array(
            'categories' => $this->getCategoryService()->getAll(),
            'posts' => $this->getPostService()->getAll($page),
            'page' => $page
        );
    }
    
    /**
     * Shows a specific post including paginated associated comments
     *
     * @Route("/post/{id}", requirements={"id"="\d+"}, name="view_post")
     * @Template()
     *
     * @param integer $id   The unique identifier of the requested post
     * @return array
     */
    public function viewAction($id)
    {
        $post = $this->getPostService()->get($id);

        // make sure the current user can view a post in this category
        if (!$this->getCategoryService()->canAccess($post->getCategory())) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $addCommentResponse = '';
        if ($this->getPostService()->canCommentOn($post)) {
            /* @var \Symfony\Component\HttpFoundation\Response $addCommentResponse */
            $addCommentResponse = $this->forward('EpixaTalkfestBundle:Comment:add', array('post' => $post));
            if ($addCommentResponse->isRedirection()) {
                return $addCommentResponse;
            }
        }

        return array(
            'form' => $addCommentResponse->getContent(),
            'post' => $post,
            'comments' => $this->getCommentService()->getByPost($post)
        );
    }

    /**
     * @Route("/post/add", name="add_post")
     * @Template()
     * @Secure(roles="ROLE_USER")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function addAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $category = null;
        $post = new Post($user);

        $categoryId = $request->query->get('c');
        if ($categoryId) {
            $category = $this->getCategoryService()->get($categoryId);
            $post->setCategory($category);
        }

        $form = $this->createForm(new PostType(), $post);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                if (!$this->getCategoryService()->canAccess($post->getCategory())) {
                    throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
                }
                $this->getPostService()->add($post);

                $this->get('session')->setFlash('notice', 'Post created');
                return $this->redirect($this->generateUrl('view_post', array('id' => $post->getId())));
            }
        }

        return array(
            'form' => $form->createView(),
            'category' => $category,
            'cancel' => $request->query->get('cancel')
        );
    }

    /**
     * @Route("/post/edit/{id}", requirements={"id"="\d+"}, name="edit_post")
     * @Template()
     * @Secure(roles="ROLE_USER")
     *
     * @param integer $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function editAction($id, Request $request)
    {
        $service = $this->getPostService();
        $post = $service->get($id);

        if (!$this->getPostService()->canEdit($post)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $form = $this->createForm(new PostType(), $post);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                if (!$this->getCategoryService()->canAccess($post->getCategory())) {
                    throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
                }
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
     * @Route("/post/delete/{id}", requirements={"id"="\d+"}, name="delete_post")
     * @Template()
     * @Secure(roles="ROLE_ADMIN")
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
