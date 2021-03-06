<?php

namespace AppBundle\Controller;

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use AppBundle\Entity\Meeting;
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $allMeetingObjects = $em->findAllMeeting();
        return $this->render('Page/index.html.twig', ['menucourse' => $allMeetingObjects]);


    }

    public function MenuAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $allMeetingObjects= $em->getRepository("AppBundle:Meeting")->findAll();
        return $this->render('Page/menucourse.html.twig', ['menucourse' => $allMeetingObjects]);
    }

    public function MenuresultAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            'SELECT r FROM AppBundle:Meeting r 
             WHERE r.date <:date')->setParameter('date', new DateTime('Now'));
        $pastMeetingObject=$query->getResult();
        return $this->render('Page/menuresult.html.twig', ['menucourse' => $pastMeetingObject]);
    }

    public function NextEventAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query=$em->createQuery(
            'SELECT r FROM AppBundle:Meeting r 
             WHERE r.date >:date')->setParameter('date', new DateTime('Now'));
        $futureMeetingObjects=$query->getResult();
        return $this->render('Page/nextEvent.html.twig', ['nextmeeting' => $futureMeetingObjects]);
    }

}

