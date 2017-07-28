<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Result;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\MeetingType;
use AppBundle\Entity\Meeting;

    class AdminController extends Controller
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
            $meetingRepository = $em->getRepository("AppBundle:Meeting");
            $meetingObject= $meetingRepository->findBy(array('id'=> $id));
            $resultRepository=$em->getRepository("AppBundle:Result");
            $athletes = $resultRepository->findBy( array('meeting'=> $id), array('points'=>'DESC'));

            /*check if the request is AJAX*/
            if ($request->isXmlHttpRequest()){
                $time=$request->get('time');
                $points=$request->get('points');
                $athleteid=$request->get('athleteid');
                $meetingid=$request->get('meetingid');
                /* call doctrine */
                $em = $this->getDoctrine()->getManager();

                /* get the object meeting*/
                $meetingRepository = $em->getRepository("AppBundle:Meeting");
                $meetingObject= $meetingRepository->findOneBy(array('id'=> $meetingid));

                /*get the object athlete*/
                $athleteRepository=$em->getRepository("AppBundle:Athlete");
                $athleteObject=$athleteRepository->findOneBy(array('id'=>$athleteid));

                /*get the line that contain both data */
                $ResultRepository=$em->getRepository(Result::class);
                $resultline=$ResultRepository->findOneBy(array('athlete'=>$athleteObject,'meeting'=>$meetingObject));

                /*Update the information to BDD*/
                $resultline->setAthlete($athleteObject);
                $resultline->setPoints($points);
                $resultline->setMeeting($meetingObject);
                $resultline->setTime($time);
                $em->persist($resultline);
                $em->flush();
            }
            return $this->render('Page/resultAdd.html.twig',['result'=>$meetingObject, 'athletes'=>$athletes]);

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
                return $this->render('/Page/index.html.twig');
            }
            /* render the form*/
            return $this->render('/Page/courseAdd.html.twig', [
                'MeetingType'=>$form->createView()
            ]);
        }



}
