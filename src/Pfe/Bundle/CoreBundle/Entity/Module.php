<?php

namespace Pfe\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Module
 *
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"mooc_id"})})
 * @ORM\Entity(repositoryClass="Pfe\Bundle\CoreBundle\Entity\ModuleRepository")
 */
class Module
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
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255, nullable=true)
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="mooc_id", type="integer")
     */
    private $mooc_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="section_mooc_id", type="integer")
     */
    private $section_mooc_id;

    /**
     * @var Section
     *
     * @ORM\ManyToOne(targetEntity="Section", cascade={"persist","merge", "detach"})
     * @Assert\Valid()
     */
    private $section;

    function __construct($name = null, $type = null, Section $section = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->section = $section;
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
     *
     * @return string
     */
    function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     * @return Module
     */
    function setName($name)
    {
        $this->name = trim($name);
        return $this;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Module
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @return Section
     */
    function getSection()
    {
        return $this->section;
    }

    /**
     *
     * @param Section $section
     * @return Module
     */
    function setSection(Section $section = null)
    {
        $this->section = $section;
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

    function getSectionMoocId()
    {
        return $this->section_mooc_id;
    }

    function setSectionMoocId($section_mooc_id)
    {
        $this->section_mooc_id = intval($section_mooc_id);
        return $this;
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

}
