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

        $id=$request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $meeting = $em->getRepository("AppBundle:Meeting");
        $meetingName= $meeting->findBy(array('id'=> $id));
        $repository=$em->getRepository("AppBundle:Result");
        $athletes = $repository->findBy( array('meeting'=> $id), array('points'=>'DESC'));

        return $this->render('page/resultAdd.html.twig',['result'=>$meetingName, 'athletes'=>$athletes]);

    }


    /**
     * @Route("/temps", name="courseTemps")
     */
    public function CourseTempsAction(Request $request)
    {


        return $this->render('page/courseTemps.html.twig');

    }
}
