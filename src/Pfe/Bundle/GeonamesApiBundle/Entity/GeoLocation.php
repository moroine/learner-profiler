<?php

namespace Pfe\Bundle\GeonamesApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Place
 *
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"isoAlpha2"})})
 * @ORM\Entity
 */
class GeoLocation
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
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="isoAlpha2", type="string")
     */
    private $isoAlpha2;

    /**
     * @var string
     *
     * @ORM\Column(name="isoAlpha3", type="string")
     */
    private $isoAlpha3;

    /**
     * @var string
     *
     * @ORM\Column(name="fips", type="string")
     */
    private $fips;

    /**
     * @var string
     *
     * @ORM\Column(name="capital", type="string")
     */
    private $capital;

    /**
     * @var string
     *
     * @ORM\Column(name="continent", type="string")
     */
    private $continent;

    function getId()
    {
        return $this->id;
    }

    function getCountry()
    {
        return $this->country;
    }

    function getIsoAlpha2()
    {
        return $this->isoAlpha2;
    }

    function getIsoAlpha3()
    {
        return $this->isoAlpha3;
    }

    function getFips()
    {
        return $this->fips;
    }

    function getCapital()
    {
        return $this->capital;
    }

    function getContinent()
    {
        return $this->continent;
    }

    function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    function setCountry($country)
    {
        $this->country = strtolower($country);
        return $this;
    }

    function setIsoAlpha2($isoAlpha2)
    {
        $this->isoAlpha2 = strtoupper($isoAlpha2);
        return $this;
    }

    function setIsoAlpha3($isoAlpha3)
    {
        $this->isoAlpha3 = strtoupper($isoAlpha3);
        return $this;
    }

    function setFips($fips)
    {
        $this->fips = strtoupper($fips);
        return $this;
    }

    function setCapital($capital)
    {
        $this->capital = strtolower($capital);
        return $this;
    }

    function setContinent($continent)
    {
        $this->continent = strtolower($continent);
        return $this;
    }

}
