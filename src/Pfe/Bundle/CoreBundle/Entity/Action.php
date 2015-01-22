<?php

namespace Pfe\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Action
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pfe\Bundle\CoreBundle\Entity\ActionRepository")
 *
 * @UniqueEntity(fields={"datetime", "city"}, message="message")
 */
class Action
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $datetime;

    /**
     * @var \int
     *
     * @ORM\Column(name="hour", type="integer")
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    private $hour;

    /**
     * @var \int
     *
     * @ORM\Column(name="day", type="string")
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"})
     */
    private $day;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255)
     * @Assert\Ip
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="action_type", type="string", length=255)
     */
    private $type;

    /**
     * @var Participant
     *
     * @ORM\ManyToOne(targetEntity="Participant")
     * @Assert\Valid
     */
    private $participant;

    /**
     * @var Localisation
     *
     * @ORM\ManyToOne(targetEntity="Localisation")
     * @Assert\Valid
     */
    private $localisation;

    /**
     * @var integer
     *
     * @ORM\Column(name="mooc_id", type="integer", unique=true)
     */
    private $mooc_id;

    /**
     * @var Module
     *
     * @ORM\ManyToOne(targetEntity="Module")
     * @Assert\Valid
     */
    private $module;

    function __construct(\DateTime $datetime, Participant $participant = null, Module $module = null, $ip = null)
    {
        $this->datetime = $datetime;
        $this->ip = $ip;
        $this->participant = $participant;
        // TODO: Get Localisation from ip
        // $this->localisation = $localisation;
        $this->module = $module;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return Action
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
        $this->day = $datetime->format("L");
        $this->hour = (int) $datetime->format("G");

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Action
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    function getType()
    {
        return $this->type;
    }

    function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    function getMoocId()
    {
        return $this->mooc_id;
    }

    function setMoocId($mooc_id)
    {
        $this->mooc_id = $mooc_id;
        return $this;
    }

    /**
     * Set Participant
     *
     * @param Participant $participant
     * @return Action
     */
    public function setParticipant($participant)
    {
        $this->participant = $participant;

        return $this;
    }

    /**
     * Get Participant
     *
     * @return Participant
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * Set Localisation
     *
     * @param Localisation $localisation
     * @return Action
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * Get Localisation
     *
     * @return Localisation
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * Set Module
     *
     * @param Module $module
     * @return Action
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get Module
     *
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }

    function getHour()
    {
        return $this->hour;
    }

    function getDay()
    {
        return $this->day;
    }

}
