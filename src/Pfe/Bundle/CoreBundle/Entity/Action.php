<?php

namespace Pfe\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Action
 *
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"hour", "day", "ip", "category","mooc_id"})})
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
     * @ORM\Column(name="ip", type="string", length=255, nullable=true)
     * @Assert\Ip
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255, nullable=true)
     */
    private $category;

    /**
     * @var Participant
     *
     * @ORM\ManyToOne(targetEntity="Participant", cascade={"persist","merge", "detach"})
     * @Assert\Valid
     */
    private $participant;

    /**
     * @var Localisation
     *
     * @ORM\ManyToOne(targetEntity="Localisation", cascade={"persist","merge", "detach"})
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
     * @var integer
     *
     * @ORM\Column(name="mooc_user_id", type="integer")
     */
    private $mooc_user_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="mooc_module_id", type="integer")
     */
    private $mooc_module_id;

    /**
     * @var Module
     *
     * @ORM\ManyToOne(targetEntity="Module", cascade={"persist","merge", "detach"})
     * @Assert\Valid
     */
    private $module;

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
    public function setDatetime(\DateTime $datetime)
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

    function getCategory()
    {
        return $this->category;
    }

    function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    function getMoocId()
    {
        return $this->mooc_id;
    }

    function setMoocId($mooc_id)
    {
        $this->mooc_id = (int) $mooc_id;
        return $this;
    }

    /**
     * Set Participant
     *
     * @param Participant $participant
     * @return Action
     */
    public function setParticipant(Participant $participant)
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
    public function setLocalisation(Localisation $localisation)
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

    function getMoocUserId()
    {
        return $this->mooc_user_id;
    }

    function getMoocModuleId()
    {
        return $this->mooc_module_id;
    }

    function setMoocUserId($mooc_user_id)
    {
        $this->mooc_user_id = (int) $mooc_user_id;
        return $this;
    }

    function setMoocModuleId($mooc_module_id)
    {
        $this->mooc_module_id = (int) $mooc_module_id;
        return $this;
    }

}
