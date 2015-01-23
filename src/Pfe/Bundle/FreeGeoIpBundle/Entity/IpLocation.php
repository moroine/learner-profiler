<?php

namespace Pfe\Bundle\FreeGeoIpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IpLocation
 *
 * @ORM\Table(name="ecommerce_products",indexes={@ORM\Index(name="search_idx", columns={"ip"})})
 * @ORM\Entity
 */
class IpLocation
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
     * @ORM\Column(name="ip", type="string", length=255)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="isoAlpha2", type="string", length=255)
     */
    private $isoAlpha2;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

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
     * Set ip
     *
     * @param string $ip
     * @return IpLocation
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

    /**
     * Set isoAlpha2
     *
     * @param string $isoAlpha2
     * @return IpLocation
     */
    public function setIsoAlpha2($isoAlpha2)
    {
        $this->isoAlpha2 = $isoAlpha2;

        return $this;
    }

    /**
     * Get isoAlpha2
     *
     * @return string
     */
    public function getIsoAlpha2()
    {
        return $this->isoAlpha2;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return IpLocation
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return IpLocation
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

}
