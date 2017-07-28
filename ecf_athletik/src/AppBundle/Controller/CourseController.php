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
class CourseController extends Controller
{
    /**
     * @Route("/resultat/course", name="resultatCourse")
     */
    public function ResultatCourseAction( Request $request)
    {

        $id=$request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $meetingRepository = $em->getRepository("AppBundle:Meeting");
        $meetingObject= $meetingRepository->findBy(array('id'=> $id));
        $resultRepository=$em->getRepository("AppBundle:Result");
        $athleteObject = $resultRepository->findBy( array('meeting'=> $id), array('points'=>'DESC'));

        return $this->render('Page/resultatCourse.html.twig',['result'=>$meetingObject, 'athletes'=>$athleteObject]);

    }
    /**
     * @Route("/resultat/classement", name="classement")
     */
    public function classementCourseAction()
    {

        $em = $this->getDoctrine()->getManager();

        $sql='SELECT SUM(result.points) as total, athlete.lastname, athlete.firstname FROM result inner join athlete on result.athlete_id = athlete.id inner join meeting on result.meeting_id = meeting.id WHERE YEAR(CURRENT_DATE()) = 2017 GROUP BY athlete.id ORDER BY total DESC 
';      $prepare=$em->getConnection()->prepare($sql);
        $prepare->execute();
        $resultat=$prepare->fetchAll();
        return $this->render('Page/classement.html.twig',['classement'=>$resultat]);

    }

    public function SelectCourseAction(Request $request){
           $em = $this->getDoctrine()->getManager();
              $query=$em->createQuery(
                'SELECT r FROM AppBundle:Meeting r 
                 WHERE r.date >:date')->setParameter('date', new DateTime('Now'));
              $meetingObject=$query->getResult();
             return $this->render('Page/meetingSelect.html.twig',['meeting'=>$meetingObject]);

  }




}
