<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Athlete;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\Result;
use AppBundle\Form\AthleteType;
use AppBundle\Form\ResultType;
use Symfony\Component\HttpFoundation\Response;
use DateTime;
use AppBundle\Entity\User;
class courseController extends Controller
{
    /**
     * @Route("/resultat/course", name="resultatCourse")
     */
    public function ResultatCourseAction( Request $request)
    {

        $id=$request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $meeting = $em->getRepository("AppBundle:Meeting");
        $meetingName= $meeting->findBy(array('id'=> $id));
        $repository=$em->getRepository("AppBundle:Result");
        $athletes = $repository->findBy( array('meeting'=> $id), array('points'=>'DESC'));

        return $this->render('page/resultatCourse.html.twig',['result'=>$meetingName, 'athletes'=>$athletes]);

    }
    /**
     * @Route("/resultat/classement", name="classement")
     */
    public function classementCourseAction()
    {

        $em = $this->getDoctrine()->getManager();

        $sql='SELECT SUM(result.points) as total, athlete.lastname, athlete.firstname FROM result inner join athlete on result.athlete_id = athlete.id inner join meeting on result.meeting_id = meeting.id WHERE YEAR(CURRENT_DATE()) = 2017 GROUP BY athlete.id ORDER BY total DESC 
';      $toto=$em->getConnection()->prepare($sql);
        $toto->execute();
        $resultat=$toto->fetchAll();
        return $this->render('page/classement.html.twig',['classement'=>$resultat]);

    }





}
