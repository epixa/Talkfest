<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Service;

use Doctrine\ORM\NoResultException,
    Epixa\TalkfestBundle\Entity\Comment,
    Epixa\TalkfestBundle\Entity\Post,
    InvalidArgumentException;

/**
 * Service for managing comments
 *
 * @category   Talkfest
 * @package    Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class CommentService extends AbstractDoctrineService
{
    /**
     * Gets a specific comment by its unique identifier
     *
     * @throws \Doctrine\ORM\NoResultException
     * @param integer $id
     * @return \Epixa\TalkfestBundle\Entity\Comment
     */
    public function get($id)
    {
        $repo = $this->getEntityManager()->getRepository('Epixa\TalkfestBundle\Entity\Comment');
        $comment = $repo->find($id);
        if (!$comment) {
            throw new NoResultException('That comment cannot be found');
        }

        return $comment;
    }

    /**
     * Gets a page of comments associated with the given post
     * 
     * @param \Epixa\TalkfestBundle\Entity\Post $post
     * @param int|null $page
     * @return array
     */
    public function getByPost(Post $post, $page = null)
    {
        /* @var \Epixa\TalkfestBundle\Repository\CommentRepository $repo */
        $repo = $this->getEntityManager()->getRepository('Epixa\TalkfestBundle\Entity\Comment');
        $qb = $repo->getSelectQueryBuilder();

        $repo->restrictToPost($qb, $post);

        if ($page !== null) {
            $repo->restrictToPage($qb, $page);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Adds the given comment to the database
     *
     * @param \Epixa\TalkfestBundle\Entity\Comment $comment
     * @return void
     */
    public function add(Comment $comment)
    {
        $em = $this->getEntityManager();

        $em->persist($comment);
        $em->flush();
    }

    /**
     * Updates the given comment in the database
     *
     * @throws \InvalidArgumentException
     * @param \Epixa\TalkfestBundle\Entity\Comment $comment
     * @return void
     */
    public function update(Comment $comment)
    {
        $em = $this->getEntityManager();
        if (!$em->contains($comment)) {
            throw new InvalidArgumentException('Comment is not managed');
        }

        $em->persist($comment);
        $em->flush();
    }

    /**
     * Deletes the given comment from the database
     * 
     * @param \Epixa\TalkfestBundle\Entity\Comment $comment
     * @return void
     */
    public function delete(Comment $comment)
    {
        $em = $this->getEntityManager();
        $em->remove($comment);
        $em->flush();
    }
}