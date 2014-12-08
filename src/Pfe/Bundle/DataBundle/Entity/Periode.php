<?php

namespace Pfe\Bundle\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Periode
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pfe\Bundle\DataBundle\Entity\PeriodeRepository")
 */
class Periode {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     * @Assert\DateTime()
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime")
     * @Assert\DateTime()
     */
    private $end;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     * @return Periode
     */
    public function setStart($start) {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart() {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return Periode
     */
    public function setEnd($end) {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd() {
        return $this->end;
    }

}
