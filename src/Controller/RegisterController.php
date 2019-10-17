<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        if($this->getUser()) {
            return $this->redirectToRoute('dashboard');
        }
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(RegisterType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $categoryId   = $request->request->get('register')['category'];
            $parentCategory = $this->findCategory($categoryId);
            if ($parentCategory) {
                $user->addCategory($parentCategory);
            }

            $categories = $request->request->get('subCategory-level');
            if($categories){
                foreach ($categories as $category) {
                    $categoryExist = $this->findCategory($category);
                    if ($categoryExist) {
                        $user->addCategory($categoryExist);
                    }
                }
            }

            $pictureFile = $request->files->get('register')['pictureFile'];

            $pictureFileType = $pictureFile->getMimeType();
            $pictureFileType = explode('/',$pictureFileType);

            if ($pictureFile && $pictureFileType[0] == 'image') {
                $pictureName = 'p_'.uniqid() . '.' . $pictureFile->guessExtension();
                $user->setPicture($pictureName);
                $pictureFile->move(
                    $this->getParameter('picture_directory'),
                    $pictureName
                );

            }

            $videoFile = $request->files->get('register')['videoFile'];
            $videoFileType = $videoFile->getMimeType();
            $videoFileType  = explode('/',$videoFileType );

            if ($videoFile && $videoFileType[0] == 'video') {
                $videoName = 'v_'.uniqid() . '.' . $videoFile->guessExtension();
                $user->setVideo($videoName);
                $videoFile->move(
                    $this->getParameter('video_directory'),
                    $videoName
                );

            }


            $user->setPassword($encoder->encodePassword($user, $data->getPassword()));
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'You are registered successfully!');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/register.html.twig', [
            'signupForm' => $form->createView()
        ]);
    }

    protected function findCategory($categoryId){
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($categoryId);

        return $category;
    }
}
