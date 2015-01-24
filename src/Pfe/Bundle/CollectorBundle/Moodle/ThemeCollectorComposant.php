<?php

namespace Pfe\Bundle\CollectorBundle\Moodle;

use \Symfony\Component\Console\Output\OutputInterface;
use \Doctrine\ORM\EntityManager;
use \Doctrine\DBAL\Connection;
use \Pfe\Bundle\CoreBundle\Entity\Theme;

/**
 * Description of ThemeCollector
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class ThemeCollectorComposant extends AbstractCollectorComposant
{

    /**
     * {@inheritdoc }
     */
    protected static $ENTITY_CLASS_NAME = "Theme";

    /**
     *
     * @param array $data
     * @return Theme
     */
    protected function hydrateEntity($data)
    {
        $mooc_id = intval($data['id']);
        $name = trim($data['fullname']);

        $theme = $this->doctrine->getRepository("PfeCoreBundle:Theme")->findOneBy(array('mooc_id' => $mooc_id));

        if (!$theme) {
            $theme = new Theme();
            $theme->setMoocId($mooc_id);
            $this->stats["created"] ++;
        } else {
            $this->stats["updated"] ++;
        }

        $theme->setName($name);

        return $theme;
    }

    /**
     * {@inheritdoc }
     */
    protected function getQuery()
    {
        $query = "SELECT c.id, c.fullname FROM mdl_course c";

        return $query;
    }

    /**
     * {@inheritdoc }
     */
    protected function getOneQuery()
    {
        $query = "SELECT c.id, c.fullname FROM mdl_course c WHERE c.id = :mooc_id";

        return $query;
    }

}
