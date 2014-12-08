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
     * @var Periode
     *
     * @ORM\OneToOne(targetEntity="Periode")
     * @Assert\Valid()
     */
    private $periode;

    /**
     * @var Theme
     *
     * @ORM\ManyToOne(targetEntity="Theme")
     * @Assert\Valid()
     */
    private $theme;

    function __construct($name, Periode $periode, Theme $theme) {
        $this->name = $name;
        $this->periode = $periode;
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
     * @return Periode
     */
    function getPeriode() {
        return $this->periode;
    }

    /**
     *
     * @param Periode $periode
     * @return Section
     */
    function setPeriode(Periode $periode) {
        $this->periode = $periode;
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
