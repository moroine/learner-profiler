<?php

namespace Pfe\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Section
 *
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"mooc_id"})})
 * @ORM\Entity(repositoryClass="Pfe\Bundle\CoreBundle\Entity\SectionRepository")
 */
class Section
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
     * @var integer
     *
     * @ORM\Column(name="section_order", type="integer")
     */
    private $order;

    /**
     * @var integer
     *
     * @ORM\Column(name="mooc_id", type="integer")
     */
    private $mooc_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="mooc_theme_id", type="integer")
     */
    private $mooc_theme_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="mooc__id", type="array")
     */
    private $mooc_module_ids;

    /**
     * @var Theme
     *
     * @ORM\ManyToOne(targetEntity="Theme")
     * @Assert\Valid()
     */
    private $theme;

    function __construct()
    {
        $this->mooc_module_ids = array();
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
     * @return Section
     */
    public function setName($name)
    {
        $this->name = trim($name);

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
     *
     * @return integer
     */
    function getOrder()
    {
        return $this->order;
    }

    /**
     *
     * @param integer $order
     * @return \Pfe\Bundle\CoreBundle\Entity\Section
     */
    function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    function getMoocId()
    {
        return $this->mooc_id;
    }

    function setMoocId($mooc_id)
    {
        $this->mooc_id = intval($mooc_id);
        return $this;
    }

    /**
     *
     * @return Theme
     */
    function getTheme()
    {
        return $this->theme;
    }

    /**
     *
     * @param Theme $theme
     * @return Section
     */
    function setTheme(Theme $theme)
    {
        $this->theme = $theme;
        return $this;
    }

    function getMoocThemeId()
    {
        return $this->mooc_theme_id;
    }

    function getMoocModuleIds()
    {
        return $this->mooc_module_ids;
    }

    function setMoocThemeId($mooc_theme_id)
    {
        $this->mooc_theme_id = intval($mooc_theme_id);
        return $this;
    }

    function setMoocModuleIds($mooc_module_ids)
    {
        $this->mooc_module_ids = intval($mooc_module_ids);
        return $this;
    }

}
