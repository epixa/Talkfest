<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Service;

use Doctrine\ORM\NoResultException,
    Symfony\Component\Security\Acl\Domain\ObjectIdentity,
    Symfony\Component\Security\Acl\Permission\MaskBuilder,
    Symfony\Component\Security\Acl\Domain\UserSecurityIdentity,
    Epixa\TalkfestBundle\Entity\Category,
    Epixa\TalkfestBundle\Entity\Post,
    Epixa\TalkfestBundle\Entity\User,
    Epixa\TalkfestBundle\Entity\Comment;

/**
 * Service for managing posts
 *
 * @category   Talkfest
 * @package    Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class PostService extends AbstractDoctrineService
{
    /**
     * Gets a specific post by its unique identifier
     *
     * @throws \Doctrine\ORM\NoResultException
     * @param integer $id
     * @return \Epixa\TalkfestBundle\Entity\Post
     */
    public function get($id)
    {
        $repo = $this->getEntityManager()->getRepository('Epixa\TalkfestBundle\Entity\Post');
        $post = $repo->find($id);
        if (!$post) {
            throw new NoResultException('That post cannot be found');
        }

        return $post;
    }

    /**
     * Gets a page of posts
     * 
     * @param int $page
     * @return array
     */
    public function getAll($page = 1)
    {
        /* @var \Epixa\TalkfestBundle\Repository\PostRepository $repo */
        $repo = $this->getEntityManager()->getRepository('Epixa\TalkfestBundle\Entity\Post');
        $qb = $repo->getSelectQueryBuilder();

        $repo->restrictToPage($qb, $page);
        $repo->includeCategory($qb);

        return $qb->getQuery()->getResult();
    }

    /**
     * Gets the total number of posts in the system
     * 
     * @return mixed
     */
    public function getTotalPosts()
    {
        $em = $this->getEntityManager();

        /* @var \Epixa\TalkfestBundle\Repository\PostRepository $repo */
        $repo = $em->getRepository('Epixa\TalkfestBundle\Entity\Post');
        $qb = $em->createQueryBuilder()->from('Epixa\TalkfestBundle\Entity\Post', 'post');
        $qb->select('count(post.id)');
        $repo->includeCategory($qb, false);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Gets the total number of pages of posts in the system
     * 
     * @return int
     */
    public function getTotalPages()
    {
        /* @var \Epixa\TalkfestBundle\Repository\PostRepository $repo */
        $repo = $this->getEntityManager()->getRepository('Epixa\TalkfestBundle\Entity\Post');
        
        return ceil($this->getTotalPosts() / $repo->getTotalPerPage());
    }

    /**
     * Gets a page of posts associated with the given category
     * 
     * @param \Epixa\TalkfestBundle\Entity\Category $category
     * @param int $page
     * @return array
     */
    public function getByCategory(Category $category, $page = 1)
    {
        /* @var \Epixa\TalkfestBundle\Repository\PostRepository $repo */
        $repo = $this->getEntityManager()->getRepository('Epixa\TalkfestBundle\Entity\Post');
        $qb = $repo->getSelectQueryBuilder();

        $repo->restrictToCategory($qb, $category);
        $repo->restrictToPage($qb, $page);

        return $qb->getQuery()->getResult();
    }

    /**
     * Adds the given new post to the database
     *
     * @param \Epixa\TalkfestBundle\Entity\Post $post
     * @return \Epixa\TalkfestBundle\Entity\Post
     */
    public function add(Post $post)
    {
        $em = $this->getEntityManager();
        $em->persist($post);
        $em->flush();

        // creating the ACL
        $aclProvider = $this->container->get('security.acl.provider');
        $objectIdentity = ObjectIdentity::fromDomainObject($post);
        $acl = $aclProvider->createAcl($objectIdentity);

        // retrieving the security identity of the currently logged-in user
        $securityContext = $this->container->get('security.context');
        $user = $securityContext->getToken()->getUser();
        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        // grant edit access
        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_EDIT);
        $aclProvider->updateAcl($acl);

        return $post;
    }

    /**
     * Updates the given post in the database
     * 
     * @throws \InvalidArgumentException
     * @param \Epixa\TalkfestBundle\Entity\Post $post
     * @return void
     */
    public function update(Post $post)
    {
        $em = $this->getEntityManager();
        if (!$em->contains($post)) {
            throw new \InvalidArgumentException('Post is not managed');
        }

        $em->persist($post);
        $em->flush();
    }

    /**
     * Deletes the given post from the database
     *
     * All comments associated with the post are also deleted.
     *
     * @param \Epixa\TalkfestBundle\Entity\Post $post
     * @return void
     */
    public function delete(Post $post)
    {
        $aclProvider = $this->container->get('security.acl.provider');
        $objectIdentity = ObjectIdentity::fromDomainObject($post);
        $aclProvider->deleteAcl($objectIdentity);
        
        $em = $this->getEntityManager();
        $em->remove($post);
        $em->flush();
    }

    /**
     * Determines if the current user can edit the given post
     *
     * @param \Epixa\TalkfestBundle\Entity\Post $post
     * @return bool
     */
    public function canEdit(Post $post)
    {
        /* @var \Epixa\TalkfestBundle\Entity\User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        // if the user is not logged in
        if (!$user instanceof User) {
            return false;
        }

        // admins should be able to edit any post
        foreach ($user->getRoles() as $role) {
            if ((string)$role == 'ROLE_ADMIN') {
                return true;
            }
        }

        // other than admins, only the original author can edit a post
        if ($post->getAuthor()->getId() === $user->getId()) {
            return true;
        }

        return false;
    }
}