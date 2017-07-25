<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $meetingmenu = $em->getRepository("AppBundle:Meeting")->findAll();
        return $this->render('page/index.html.twig',['menucourse'=>$meetingmenu]);



    }
    public function MenuAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $meetingmenu = $em->getRepository("AppBundle:Meeting")->findAll();
        return $this->render('page/menucourse.html.twig',['menucourse'=>$meetingmenu]);
    }

<<<<<<< HEAD
    public function MainMenuAction(Request $request)
    {

    }
=======
>>>>>>> 37c2fe16a2ed19f557644d9c65e96c4cebb4220c
}

