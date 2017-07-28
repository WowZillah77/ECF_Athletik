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




class UserController extends Controller
{

    /**
     * @route("/Inscription", name="inscription")
     * @method({"POST"})
     */
    public function NewRunnerAction(Request $request)
    {
        $athleteObjectOfUser =$this->getUser()->getAthlete();
        $id=$this->getUser()->getId();
        
        if (!$athleteObjectOfUser) {
            /* create a new runner*/
            $newAthlete = new Athlete();
            $form = $this->createForm(AthleteType::class, $newAthlete);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($newAthlete);
                $em->flush();


                /*get the ID of the logged user and link the Athlete object to the User ID*/
                $user = $this->getUser();
                $user->setAthlete($newAthlete);
                $em->persist($user);
                $em->flush();
                $id=$this->getUser()->getId();
                return $this->render('/Page/RegisterCourse.html.twig', ['firstname' => $newAthlete->getFirstname(), 'lastname' => $newAthlete->getLastname(), 'id'=>$id]);
            }
            return $this->render('/Page/RegisterRunner.html.twig', ['AthleteType' => $form->createView()]);
        }
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
                                'SELECT i FROM AppBundle:Athlete i Where i.id=:Athleteid')
                                ->setParameter('Athleteid', $athleteObjectOfUser);
        $newAthlete=$query->getSingleResult();
        $fname=$newAthlete->getFirstname();
        $lname=$newAthlete->getLastname();
        return $this->render('/Page/RegisterCourse.html.twig', ['firstname' => $fname, 'lastname' => $lname, 'id'=>$id, 'athlete'=>$newAthlete]);
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
                                'SELECT i FROM AppBundle:Meeting i Where i.id=:meetingid')
                                ->setParameter('meetingid', $id);
        $meetingObject=$query->getSingleResult();
        $result=new Result();
        $result->setMeeting($meetingObject);
        $result->setAthlete($user);
        $result->setTime(0);
        $result->setPoints(0);
        $em->persist($result);
        $em->flush();

        return $this->render('/Page/signedup.html.twig');
    }

    public function SelectCourseAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
                                'SELECT r FROM AppBundle:Meeting r 
                                 WHERE r.date >:date')
                                 ->setParameter('date', new DateTime('Now'));
        $futurMeeting=$query->getResult();
        return $this->render('Page/meetingSelect.html.twig',['meeting'=>$futurMeeting]);

    }




}