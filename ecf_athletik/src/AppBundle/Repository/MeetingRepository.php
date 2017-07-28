<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class MeetingRepository extends EntityRepository
{
    public  function findAllMeeting()
    {
        return $this->getRepository("AppBundle:Meeting")->findAll();
    }
}