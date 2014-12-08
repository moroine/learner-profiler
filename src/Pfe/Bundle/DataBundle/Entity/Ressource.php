<?php

namespace Pfe\Bundle\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ressource
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pfe\Bundle\DataBundle\Entity\RessourceRepository")
 */
class Ressource {

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
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @var Section
     *
     * @ORM\ManyToOne(targetEntity="Section")
     * @Assert\Valid()
     */
    private $section;

    function __construct($name, $type, Section $section = null) {
        $this->name = $name;
        $this->type = $type;
        $this->section = $section;
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
     *
     * @return string
     */
    function getName() {
        return $this->name;
    }

    /**
     *
     * @param string $name
     * @return Ressource
     */
    function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Ressource
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     *
     * @return Section
     */
    function getSection() {
        return $this->section;
    }

    /**
     *
     * @param Section $section
     * @return Ressource
     */
    function setSection(Section $section) {
        $this->section = $section;
        return $this;
    }

}
