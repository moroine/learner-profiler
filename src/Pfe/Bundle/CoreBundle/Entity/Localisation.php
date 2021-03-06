<?php

namespace Pfe\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Localisation
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="localisation_unique", columns={"state", "city"})})
 * @ORM\Entity(repositoryClass="Pfe\Bundle\CoreBundle\Entity\LocalisationRepository")
 *
 * @UniqueEntity(fields={"state", "city"}, message="Le participant d'adresse email {{value}} existe déjà")
 */
class Localisation implements \JsonSerializable
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
     * @ORM\Column(name="state", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     *
     * @var float
     *
     * @ORM\Column(name="g_latitude", type="float", nullable=true)
     */
    private $latitude;

    /**
     *
     * @var float
     *
     * @ORM\Column(name="g_longitude", type="float", nullable=true)
     */
    private $longitude;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="iso3", type="string", nullable=true)
     */
    private $isoAlpha3;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="iso2", type="string", nullable=true)
     */
    private $isoAlpha2;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="fips", type="string", nullable=true)
     */
    private $fips;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="g_place_id", type="string", nullable=true)
     */
    private $g_place_id;

    /**
     *
     * @param type $state
     * @param type $city
     * @param type $latitude
     * @param type $longitude
     * @param type $g_place_id
     */
    public function __construct($state, $city = null, $latitude = null, $longitude = null, $g_place_id = null, $g_address = null, $g_place_results = 0)
    {
        $this->state = $state;
        $this->city = empty($city) ? null : $city;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->g_place_id = $g_place_id;
        $this->g_address = $g_address;
        $this->g_place_results = $g_place_results;
    }

    private function wd_remove_accents($str, $charset = 'utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

        return $str;
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
     * Set state
     *
     * @param string $state
     * @return Localisation
     */
    public function setState($state)
    {
        $this->state = strtolower($this->wd_remove_accents($state));

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Localisation
     */
    public function setCity($city)
    {
        $this->city = strtolower($this->wd_remove_accents($city));

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

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getG_place_id()
    {
        return $this->g_place_id;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function setG_place_id($g_place_id)
    {
        $this->g_place_id = $g_place_id;
        return $this;
    }

    function getIsoAlpha3()
    {
        return $this->isoAlpha3;
    }

    function getIsoAlpha2()
    {
        return $this->isoAlpha2;
    }

    function getFips()
    {
        return $this->fips;
    }

    function setIsoAlpha3($isoAlpha3)
    {
        $this->isoAlpha3 = strtoupper($isoAlpha3);
        return $this;
    }

    function setIsoAlpha2($isoAlpha2)
    {
        $this->isoAlpha2 = strtoupper($isoAlpha2);
        return $this;
    }

    function setFips($fips)
    {
        $this->fips = strtoupper($fips);
        return $this;
    }

    public function jsonSerialize()
    {
        $obj = new \stdClass();

        $obj->id = $this->id;
        $obj->state = $this->state;
        $obj->city = $this->city;
        $obj->g_address = $this->g_address;

        return (array) $obj;
    }

}
