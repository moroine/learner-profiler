<?php

namespace Pfe\Bundle\CollectorBundle\Moodle;

use \Symfony\Component\Console\Output\OutputInterface;
use \Pfe\Bundle\CoreBundle\Entity\Section;

/**
 * Description of ThemeCollector
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class SectionCollectorComposant extends AbstractCollectorComposant
{

    /**
     * {@inheritdoc }
     */
    protected static $ENTITY_CLASS_NAME = "Section";

    /**
     *
     * @param array $data
     * @return Section
     */
    protected function hydrateEntity($data)
    {
        $mooc_id = intval($data['id']);

        $section = $this->doctrine->getRepository("PfeCoreBundle:Section")->findOneBy(array('mooc_id' => $mooc_id));

        $order = intval($data['section']);

        if ($section === null) {
            $section = new Section();
            $this->stats['created'] ++;
        } else {
            $this->stats['updated'] ++;
        }

        $section->setMoocId($mooc_id);
        $section->setName($data["name"]);
        $section->setMoocThemeId($data['course']);
        $section->setMoocModuleIds(explode(",", $data["sequence"]));
        $section->setOrder($order);

        return $section;
    }

    /**
     *
     * @param ?string $theme_label_id Select only from one theme
     * @return string The select query
     */
    protected function getQuery($theme_label_id = null)
    {
        $query = "SELECT s.id, s.name, s.section, s.sequence, s.course FROM mdl_course_sections s WHERE s.visible=1";

        if ($theme_label_id) {
            $query .= " AND s.course=:" . $theme_label_id;
        }

        return $query;
    }

    /**
     *
     * @param integer $mooc_id Specific mooc_id
     * @param string|null $theme_label_id Select only from one theme
     * @return string The select query
     */
    protected function getOneQuery($theme_label_id = null)
    {
        $query = "SELECT s.id, s.name, s.section, s.sequence, s.course FROM mdl_course_sections s WHERE s.visible=1 AND s.id=:mooc_id";

        if ($theme_label_id) {
            $query .= " AND s.course=:theme_mooc_id";
        }

        return $query;
    }

}
