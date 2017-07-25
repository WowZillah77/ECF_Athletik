<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $meetingmenu = $em->getRepository("AppBundle:Meeting")->findAll();
        return $this->render('page/index.html.twig', ['menucourse' => $meetingmenu]);


    }

    public function MenuAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $meetingmenu = $em->getRepository("AppBundle:Meeting")->findAll();
        return $this->render('page/menucourse.html.twig', ['menucourse' => $meetingmenu]);
    }

    public function MenuresultAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            'SELECT r FROM AppBundle:Meeting r 
             WHERE r.date <:date')->setParameter('date', new DateTime('Now'));
        $meeting=$query->getResult();
        return $this->render('page/menuresult.html.twig', ['menucourse' => $meeting]);
    }

}

