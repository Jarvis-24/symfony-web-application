<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser()->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->redirectToRoute('admin');
        }
        /*foreach ($this->getUser()->getCategories() as $category) {
            dump($category);
        }die;*/
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
