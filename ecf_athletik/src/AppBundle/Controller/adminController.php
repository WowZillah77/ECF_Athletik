<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Result;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\MeetingType;
use AppBundle\Entity\Meeting;

class adminController extends Controller
{

    /*----------------------------------------------------*
     *         Add Points and time                        *
     * -------------------------------------------------- */


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

    /*----------------------------------------------------*
     *         create a race                              *
     * -------------------------------------------------- */

    /**
     * @route("/newrace/", name="new_Race")
     * @method({"POST"})
     */
    public function NewRaceAction(Request $request){

        /* create a new meeting object */
                $race = new Meeting();

        /* create the form for the object*/
                $form = $this ->createForm(MeetingType::class, $race);
                $form->handleRequest($request);

        /*check if the form is valid and submitted*/
                if ($form->isSubmitted() && $form->isValid()){

            /*send the new meeting object to BDD*/
                $em = $this->getDoctrine()->getManager();
                $em->persist($race);
                $em->flush();

            /*return to index*/
                return $this->render('/page/index.html.twig');
        }
        /* render the form*/
                return $this->render('/page/courseAdd.html.twig', [
                'MeetingType'=>$form->createView()
                ]);
    }


}
