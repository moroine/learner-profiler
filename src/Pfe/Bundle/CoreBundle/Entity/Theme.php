<?php

namespace Pfe\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Theme
 *
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"mooc_id"})})
 * @ORM\Entity(repositoryClass="Pfe\Bundle\CoreBundle\Entity\ThemeRepository")
 */
class Theme
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
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="mooc_id", type="integer", unique=true)
     */
    private $mooc_id;

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
     * @return Theme
     */
    public function setName($name)
    {
        $this->name = $name;

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
    function getMoocId()
    {
        return $this->mooc_id;
    }

    /**
     *
     * @param integer $mooc_id
     * @return \Pfe\Bundle\CoreBundle\Entity\Theme
     */
    function setMoocId($mooc_id)
    {
        $this->mooc_id = $mooc_id;

        return $this;
    }

}
