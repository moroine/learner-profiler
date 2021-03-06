<?php

namespace Pfe\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Participant
 *
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"email", "mooc_id"})})
 * @ORM\Entity(repositoryClass="Pfe\Bundle\CoreBundle\Entity\ParticipantRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="role", type="string")
 * @ORM\DiscriminatorMap({"etudiant" = "Etudiant", "staff" = "Staff", "apprenant" = "Apprenant", "participant" = "Participant"})
 *
 * @UniqueEntity(fields="email", message="Le participant d'adresse email {{value}} existe déjà")
 */
class Participant implements \JsonSerializable
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type(type="string", message="La valeur {{ value }} n'est pas un type {{ type }} valide.")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email(message = "'{{ value }}' n'est pas un email valide.")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $city;

    /**
     *
     * @var \Datetime
     *
     * @ORM\Column(name="last_access", type="datetime")
     */
    private $last_access;

    /**
     * @var integer
     *
     * @ORM\Column(name="mooc_id", type="integer", unique=true)
     */
    private $mooc_id;

    /**
     *
     * @var Localisation
     *
     * @ORM\ManyToOne(targetEntity="Localisation", cascade={"persist", "merge"})
     * @Assert\Valid
     */
    private $home;

    function __construct()
    {
        $this->id = NULL;
        $this->name = NULL;
        $this->email = NULL;
        $this->country = NULL;
        $this->city = NULL;
        $this->last_access = NULL;
        $this->mooc_id = NULL;
        $this->home = NULL;
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
     * Set name
     *
     * @param string $name
     * @return Participant
     */
    public function setName($name)
    {
        $this->name = trim(strtolower($name));

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Participant
     */
    public function setEmail($email)
    {
        $this->email = trim(strtolower($email));

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
     * Get Home Localisation
     *
     * @return Localisation
     */
    function getHome()
    {
        return $this->home;
    }

    /**
     * Set Home Localisation
     *
     * @param Localisation $home
     * @return Participant
     */
    function setHome(Localisation $home = null)
    {
        $this->home = $home;
        return $this;
    }

    public function getLast_access()
    {
        return $this->last_access;
    }

    public function setLast_access(\Datetime $last_access = null)
    {
        $this->last_access = $last_access;
        return $this;
    }

    function getCountryCode()
    {
        return $this->country;
    }

    function getCity()
    {
        return $this->city;
    }

    function setCountryCode($country)
    {
        $this->country = trim(strtolower($country));
        return $this;
    }

    function setCity($city)
    {
        $this->city = trim(strtolower($city));
        return $this;
    }

    public function jsonSerialize()
    {
        $obj = new \stdClass();

        $obj->id = $this->getId();
        $obj->name = $this->getName();
        $obj->email = $this->getEmail();
        $obj->last_access = $this->getLast_access();
        $obj->home_id = $this->getHome();
        $obj->country = $this->getCountryCode();
        $obj->city = $this->getCity();
        return (array) $obj;
    }

}
