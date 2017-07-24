<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Athlete;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\Result;
use AppBundle\Form\MeetingType;
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

    /**
     * @route("/Inscription", name="inscription")
     * @method({"POST"})
     */
    public function NewRunnerAction(Request $request)
    {
        $athleteid =$this->getUser()->getAthlete();
        if (!$athleteid) {
            /* create a new runner*/
            $Athlete = new Athlete();
            $form = $this->createForm(AthleteType::class, $Athlete);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($Athlete);


                /* keep the info from the runner*/
                $fname = $form['firstname']->getData();
                $lname = $form['lastname']->getData();
                $birth = $form['birthdate']->getData();
                $em->flush();


                /*search the Athlete ID based on the info from the form*/
                $query = $em->createQuery(
                    'SELECT i FROM AppBundle:Athlete i 
                 WHERE i.firstname=:firstname AND i.lastname=:lastname AND i.birthdate=:birth')->setParameter('firstname', $fname)
                    ->setParameter('lastname', $lname)
                    ->setParameter('birth', $birth);
                $athletic = $query->getSingleResult();


                /*get the ID of the logged user and link the Athlete ID to the User ID*/
                $user = $this->getUser();
                $user->setAthlete($athletic);
                $em->persist($user);
                $em->flush();
                return $this->render('/page/RegisterCourse.html.twig', ['firstname' => $fname, 'lastname' => $lname]);
            }
            return $this->render('/page/RegisterRunner.html.twig', [
                'AthleteType' => $form->createView()
            ]);
        }


        return $this->render('/page/RegisterCourse.html.twig', ['athleteid'=> $athleteid]);
    }









    public function SelectCourseAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            'SELECT r FROM AppBundle:Meeting r 
             WHERE r.date >:date')->setParameter('date', new DateTime('Now'));
        $meeting=$query->getResult();
        return $this->render('page/meetingSelect.html.twig',['meeting'=>$meeting]);

    }
    /**
     * @route("/Inscription", name="inscription")
     * @method({"POST"})
     */

    public function RegisterCourseAction(Request $request){
       if(){

       }
        return $this->render('page/meetingSelect.html.twig',['meeting'=>$meeting]);

    }



}
