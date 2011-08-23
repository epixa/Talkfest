<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Symfony\Component\HttpFoundation\Request,
    Epixa\TalkfestBundle\Entity\Category,
    Epixa\TalkfestBundle\Form\Type\CategoryType,
    Epixa\TalkfestBundle\Form\Type\DeleteCategoryType,
    Epixa\TalkfestBundle\Model\CategoryDeletionOptions;

/**
 * Controller managing categories
 *
 * @category   Talkfest
 * @package    Controller
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class CategoryController extends Controller
{
    /**
     * Shows the posts in a specific category
     * 
     * @Route("/category/{id}", requirements={"id"="\d+"}, name="view_category")
     * @Route("/category/{id}/{page}", requirements={"id"="\d+", "page"="\d+"}, name="view_category_page")
     * @Template()
     *
     * @param integer $id   The unique identifier of the requested category
     * @param integer $page The page of postss to display for this category
     * @return array
     */
    public function viewAction($id, $page = 1)
    {
        $category = $this->getCategoryService()->get($id);

        return array(
            'category' => $category,
            'posts' => $this->getPostService()->getByCategory($category, $page),
            'page' => $page
        );
    }

    /**
     * @Route("/category/add", name="add_category")
     * @Template()
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array|\Symfony\Bundle\FrameworkBundle\Controller\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(new CategoryType(), $category);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $this->getCategoryService()->add($category);

                $this->get('session')->setFlash('notice', 'Category created');
                return $this->redirect($this->generateUrl('talkfest_home'));
            }
        }
        
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/category/edit/{id}", requirements={"id"="\d+"}, name="edit_category")
     * @Template()
     *
     * @param integer $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array|\Symfony\Bundle\FrameworkBundle\Controller\RedirectResponse
     */
    public function editAction($id, Request $request)
    {
        $service = $this->getCategoryService();
        $category = $service->get($id);

        $form = $this->createForm(new CategoryType(), $category);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $service->update($category);

                $this->get('session')->setFlash('notice', 'Category updated');
                return $this->redirect($this->generateUrl('view_category', array('id' => $category->getId())));
            }
        }

        return array(
            'form' => $form->createView(),
            'category' => $category
        );
    }

    /**
     * @Route("/category/delete/{id}", requirements={"id"="\d+"}, name="delete_category")
     * @Template()
     *
     * @param $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array|\Symfony\Bundle\FrameworkBundle\Controller\RedirectResponse
     */
    public function deleteAction($id, Request $request)
    {
        $service = $this->getCategoryService();
        $category = $service->get($id);

        $deletionOptions = new CategoryDeletionOptions($category);
        $form = $this->createForm(new DeleteCategoryType(), $deletionOptions);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $service->delete($deletionOptions);

                $this->get('session')->setFlash('notice', 'Category deleted');
                return $this->redirect($this->generateUrl('talkfest_home'));
            }
        }

        return array(
            'form' => $form->createView(),
            'category' => $category
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
}
