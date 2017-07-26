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


class userController extends Controller
{

    /**
     * @route("/Inscription", name="inscription")
     * @method({"POST"})
     */
    public function NewRunnerAction(Request $request)
    {
        $athleteid =$this->getUser()->getAthlete();
        $id=$this->getUser()->getId();
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
                $id=$this->getUser()->getId();
                return $this->render('/page/RegisterCourse.html.twig', ['firstname' => $fname, 'lastname' => $lname, 'id'=>$id]);
            }
            return $this->render('/page/RegisterRunner.html.twig', [
                'AthleteType' => $form->createView()
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            'SELECT i FROM AppBundle:Athlete i Where i.id=:Athleteid'
        )->setParameter('Athleteid', $athleteid);
        $Athlete=$query->getSingleResult();
        $fname=$Athlete->getFirstname();
        $lname=$Athlete->getLastname();
        return $this->render('/page/RegisterCourse.html.twig', ['firstname' => $fname, 'lastname' => $lname, 'id'=>$id, 'athlete'=>$Athlete]);
    }



    /* send that runner has registered to the race to BDD */
    /**
     * @route("/signedup/{id}", name="signedUp")
     * @method({"POST"})
     */
    public function signedUpAction(Request $request, $id)
    {
        $user = $this->getUser()->getAthlete();
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            'SELECT i FROM AppBundle:Meeting i Where i.id=:meetingid'
        )->setParameter('meetingid', $id);
        $meeting=$query->getSingleResult();
        $result=new Result();
        $result->setMeeting($meeting);
        $result->setAthlete($user);
        $result->setTime(0);
        $result->setPoints(0);
        $em->persist($result);
        $em->flush();

        return $this->render('/page/signedup.html.twig');
    }









    public function SelectCourseAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            'SELECT r FROM AppBundle:Meeting r 
             WHERE r.date >:date')->setParameter('date', new DateTime('Now'));
        $meeting=$query->getResult();
        return $this->render('page/meetingSelect.html.twig',['meeting'=>$meeting]);

    }




}