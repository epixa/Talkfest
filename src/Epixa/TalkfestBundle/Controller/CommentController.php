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
    Epixa\TalkfestBundle\Entity\Comment,
    Epixa\TalkfestBundle\Form\Type\CommentType,
    RuntimeException;

/**
 * Controller managing comments
 *
 * @category   Talkfest
 * @package    Controller
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class CommentController extends Controller
{
    /**
     * @Template()
     * @Secure(roles="ROLE_USER")
     *
     * @param \Epixa\TalkfestBundle\Entity\Post $post
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function addAction(Post $post, Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $comment = new Comment($post, $user);

        $form = $this->createForm(new CommentType(), $comment);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $this->getCommentService()->add($comment);

                $this->get('session')->setFlash('notice', 'Comment created');
                $baseUrl = $this->generateUrl('view_post', array('id' => $post->getId()));
                return $this->redirect($baseUrl . '#comment-' . $comment->getId());
            }
        }

        return array(
            'form' => $form->createView(),
            'post' => $post
        );
    }

    /**
     * @Route("/comment/edit/{id}", requirements={"id"="\d+"}, name="edit_comment")
     * @Template()
     * @Secure(roles="ROLE_USER")
     *
     * @param integer $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function editAction($id, Request $request)
    {
        $service = $this->getCommentService();
        $comment = $service->get($id);

        $form = $this->createForm(new CommentType(), $comment);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $service->update($comment);

                $this->get('session')->setFlash('notice', 'Comment updated');
                $baseUrl = $this->generateUrl('view_post', array('id' => $comment->getPost()->getId()));
                return $this->redirect($baseUrl . '#comment-' . $comment->getId());
            }
        }

        return array(
            'form' => $form->createView(),
            'comment' => $comment
        );
    }

    /**
     * @Route("/comment/delete/{id}", requirements={"id"="\d+"}, name="delete_comment")
     * @Template()
     * @Secure(roles="ROLE_USER")
     *
     * @param integer $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function deleteAction($id, Request $request)
    {
        $service = $this->getCommentService();
        $comment = $service->get($id);

        if ($request->getMethod() == 'POST') {
            $post = $comment->getPost();
            $service->delete($comment);

            $this->get('session')->setFlash('notice', 'Comment deleted');
            return $this->redirect($this->generateUrl('view_post', array('id' => $post->getId())));
        }

        return array(
            'comment' => $comment
        );
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
