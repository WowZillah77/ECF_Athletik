<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class adminController extends Controller
{
    /**
     * @Route("/ajouter", name="courseadd")
     */
    public function CourseAddAction(Request $request)
    {
        return $this->render('page/courseAdd.html.twig');

    }

    /**
     * @Route("/resultatTemp", name="Addpoints")
     */
    public function PointsAddAction(Request $request)
    {
        return $this->render('page/resultAdd.html.twig');

    }

}
