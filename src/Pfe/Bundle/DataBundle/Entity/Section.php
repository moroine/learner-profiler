<?php

namespace Pfe\Bundle\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Section
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pfe\Bundle\DataBundle\Entity\SectionRepository")
 */
class Section {

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
     * @var integer
     *
     * @ORM\Column(name="section_order", type="integer")
     */
    private $order;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration_days", type="integer")
     */
    private $duration_days;

    /**
     * @var Theme
     *
     * @ORM\ManyToOne(targetEntity="Theme")
     * @Assert\Valid()
     */
    private $theme;

    function __construct($name, $order, $duration_days, Theme $theme) {
        $this->name = $name;
        $this->duration_days = $duration_days;
        $this->order = $order;
        $this->theme = $theme;
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
     * Set name
     *
     * @param string $name
     * @return Section
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     *
     * @return integer
     */
    function getOrder() {
        return $this->order;
    }

    /**
     *
     * @return integer
     */
    function getDuration_days() {
        return $this->duration_days;
    }

    /**
     *
     * @param integer $order
     * @return \Pfe\Bundle\DataBundle\Entity\Section
     */
    function setOrder($order) {
        $this->order = $order;
        return $this;
    }

    /**
     *
     * @param integer $duration_days
     * @return \Pfe\Bundle\DataBundle\Entity\Section
     */
    function setDuration_days($duration_days) {
        $this->duration_days = $duration_days;
        return $this;
    }

    /**
     *
     * @return Theme
     */
    function getTheme() {
        return $this->theme;
    }

    /**
     *
     * @param Theme $theme
     * @return Section
     */
    function setTheme(Theme $theme) {
        $this->theme = $theme;
        return $this;
    }

}
