<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\SubCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function categories()
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Category::class)->findCategories();

        return $this->render('admin/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/category/add", name="category_add")
     */
    public function addCategory(Request $request)
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $em = $this->getDoctrine()->getManager();
        $category = new Category();
        $form = $this->createForm(CategoryType::class,$category );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Category added successfully!');

            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/add_category.html.twig', [
            'categoryForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/category/edit", name="category_edit")
     */
    public function editCategory(Request $request)
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryType::class , $category,[]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Category added successfully!');

            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/edit_category.html.twig', [
            'categoryForm' => $form->createView(),
            'category' => $category
        ]);
    }


    /**
     * @Route("/admin/category/remove", name="category_remove")
     */
    public function deleteCategory(Request $request)
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $id = $request->query->get('id');
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $category = $em->getRepository(Category::class)->find($id);
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('admin');

    }

    /**
     * @Route("/admin/sub-category", name="sub_category_list")
     */
    public function subCategories()
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $em = $this->getDoctrine()->getManager();
        $subCategories = $em->getRepository(Category::class)->findSubCategories();

        return $this->render('admin/subCategory/index.html.twig', [
            'subCategories' => $subCategories,
        ]);
    }

    /**
     * @Route("/admin/sub-category/add", name="sub_category_add")
     */
    public function addSubCategory(Request $request)
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $em = $this->getDoctrine()->getManager();
        $subCategory = new Category();
        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em->persist($subCategory);
            $em->flush();
            $this->addFlash('success', 'Sub Category added successfully!');

            return $this->redirectToRoute('sub_category_list');
        }

        return $this->render('admin/subCategory/add_sub_category.html.twig', [
            'subCategoryForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/sub-category/edit", name="sub_category_edit")
     */
    public function editSubCategory(Request $request)
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();

        $subCategory = $em->getRepository(Category::class)->find($id);
        $form = $this->createForm(SubCategoryType::class , $subCategory ,[]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($subCategory);
            $em->flush();
            $this->addFlash('success', 'Sub Category updated successfully!');

            return $this->redirectToRoute('sub_category_list');
        }

        return $this->render('admin/subCategory/edit_sub_category.html.twig', [
            'subCategoryForm' => $form->createView(),
            'subCategory' => $subCategory
        ]);
    }

    /**
     * @Route("/admin/sub-category/remove", name="sub_category_remove")
     */
    public function deleteSubCategory(Request $request)
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $id = $request->query->get('id');
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $subCategory = $em->getRepository(Category::class)->find($id);
            $em->remove($subCategory);
            $em->flush();
        }

        return $this->redirectToRoute('sub_category_list');

    }


    /**
     * @Route("/sub-categories", name="sub_categories_list")
     */
    public function subCategoriesList(Request $request)
    {
        $categoryid = $request->query->get("categoryid");
        $dataId = $request->query->get("dataId");
        //echo $categoryid;
        $responseArray = [];
        $em = $this->getDoctrine()->getManager();
        $subCategories = $em->getRepository(Category::class)->findBy(['parentCategory' => $categoryid]);
        if ($subCategories) {
            foreach($subCategories as $subCategory) {
                $responseArray[] = array(
                    "id" => $subCategory->getId(),
                    "name" => $subCategory->getCategoryName()
                );
            }
        }
        return new JsonResponse($responseArray);
    }
}
