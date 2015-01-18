<?php

namespace Pfe\Bundle\CollectorBundle\Collector\Builder;

use Symfony\Component\Console\Output\OutputInterface;
use \Pfe\Bundle\CoreBundle\Entity\Theme;

/**
 * Description of ThemeMoocBuilder
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class ThemeMoocBuilder extends AbstractMoocBuilder {

    public function __construct(\Doctrine\Bundle\DoctrineBundle\Registry $doctrine) {
        parent::__construct($doctrine);
        $this->entityClassname = "Theme";
    }

    /**
     *
     * @param type $data
     * @return \Pfe\Bundle\CoreBundle\Entity\Theme
     */
    public function buildTheme(OutputInterface $output, $data) {
        $id = intval($data['id']);
        $name = trim($data['fullname']);

        $theme = $this->getOneBy(array("course_id" => $id));
        $theme->setName($name);
        $theme->setCourseId($id);

        return $theme;
    }

    protected function createEntity() {
        return new Theme();
    }

}
