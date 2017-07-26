<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Result;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\MeetingType;

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
    /*Get the Result from the selected Race*/
        $id=$request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $meeting = $em->getRepository("AppBundle:Meeting");
        $meetingName= $meeting->findBy(array('id'=> $id));
        $repository=$em->getRepository("AppBundle:Result");
        $athletes = $repository->findBy( array('meeting'=> $id), array('points'=>'DESC'));

 /*check if the request is AJAX*/
            if ($request->isXmlHttpRequest()){
                $time=$request->get('time');
                $points=$request->get('points');
                $athleteid=$request->get('athleteid');
                $meetingid=$request->get('meetingid');
/* call doctrine */
                $em = $this->getDoctrine()->getManager();

/* get the object meeting*/
        $meeting = $em->getRepository("AppBundle:Meeting");
        $meetingObject= $meeting->findOneBy(array('id'=> $meetingid));

/*get the object athlete*/
        $runner=$em->getRepository("AppBundle:Athlete");
        $athleteObject=$runner->findOneBy(array('id'=>$athleteid));

/*get the line that contain both data */
        $resultTable=$em->getRepository(Result::class);
        $result=$resultTable->findOneBy(array('athlete'=>$athleteObject,'meeting'=>$meetingObject));

/*Update the information to BDD*/
       $result->setAthlete($athleteObject);
        $result->setPoints($points);
        $result->setMeeting($meetingObject);
        $result->setTime($time);
                $em->persist($result);
                $em->flush();
            }
        return $this->render('page/resultAdd.html.twig',['result'=>$meetingName, 'athletes'=>$athletes]);

    }

    /**
     * @route("/newrace/", name="new_Race")
     * @method({"POST"})
     */
    public function NewRaceAction(Request $request){
        $race = new Meeting();
        $form = $this ->createForm(MeetingType::class, $race);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($race);
            $em->flush();
            return $this->render('/page/index.html.twig');
        }
        return $this->render('/page/courseAdd.html.twig', [
            'MeetingType'=>$form->createView()
        ]);
    }


}
