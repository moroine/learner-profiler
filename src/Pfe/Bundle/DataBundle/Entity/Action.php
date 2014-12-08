<?php

namespace Pfe\Bundle\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Action
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pfe\Bundle\DataBundle\Entity\ActionRepository")
 */
class Action {

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
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255)
     * @Assert\Ip
     */
    private $ip;

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
     * @var Ressource
     *
     * @ORM\ManyToOne(targetEntity="Ressource")
     * @Assert\Valid
     */
    private $ressource;

    function __construct(\DateTime $datetime, Participant $participant, Ressource $ressource = null, $ip = null) {
        $this->datetime = $datetime;
        $this->ip = $ip;
        $this->participant = $participant;
        // TODO: Get Localisation from ip
        // $this->localisation = $localisation;
        $this->ressource = $ressource;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return Action
     */
    public function setDatetime($datetime) {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime() {
        return $this->datetime;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Action
     */
    public function setIp($ip) {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * Set Participant
     *
     * @param Participant $participant
     * @return Action
     */
    public function setParticipant($participant) {
        $this->participant = $participant;

        return $this;
    }

    /**
     * Get Participant
     *
     * @return Participant
     */
    public function getParticipant() {
        return $this->participant;
    }

    /**
     * Set Localisation
     *
     * @param Localisation $localisation
     * @return Action
     */
    public function setLocalisation($localisation) {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * Get Localisation
     *
     * @return Localisation
     */
    public function getLocalisation() {
        return $this->localisation;
    }

    /**
     * Set Ressource
     *
     * @param Ressource $ressource
     * @return Action
     */
    public function setRessource($ressource) {
        $this->ressource = $ressource;

        return $this;
    }

    /**
     * Get Ressource
     *
     * @return Ressource
     */
    public function getRessource() {
        return $this->ressource;
    }

}
