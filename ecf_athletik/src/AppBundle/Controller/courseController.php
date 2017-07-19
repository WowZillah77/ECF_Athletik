<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class courseController extends Controller
{
    /**
     * @Route("/resultat/course", name="resultatCourse")
     */
    public function ResultatCourseAction( Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $meetingName = $em->getRepository("AppBundle:Meeting")->findAll();
        $repository=$em->getRepository("AppBundle:Result");
        $athletes = $repository->findBy( array('meeting'=> '1'), array('points'=>'DESC'));

        return $this->render('page/resultatCourse.html.twig',['result'=>$meetingName, 'athletes'=>$athletes]);

    }
    /**
     * @Route("/resultat/classement", name="classement")
     */
    public function classementCourseAction()
    {
        // replace this example code with whatever you need
        return $this->render('page/classement.html.twig');

    }

}
