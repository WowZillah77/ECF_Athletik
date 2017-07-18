<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Result
 *
 * @ORM\Table(name="result", indexes={@ORM\Index(name="athlete_id", columns={"athlete_id"}), @ORM\Index(name="meeting_id", columns={"meeting_id"})})
 * @ORM\Entity
 */
class Result
{
    /**
     * @var float
     *
     * @ORM\Column(name="time", type="float", precision=10, scale=0, nullable=false)
     */
    private $time;

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer", nullable=false)
     */
    private $points;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Athlete
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Athlete")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="athlete_id", referencedColumnName="id")
     * })
     */
    private $athlete;

    /**
     * @var \AppBundle\Entity\Meeting
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Meeting")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="meeting_id", referencedColumnName="id")
     * })
     */
    private $meeting;


}

