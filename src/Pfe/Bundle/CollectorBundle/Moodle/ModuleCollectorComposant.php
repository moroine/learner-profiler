<?php

namespace Pfe\Bundle\CollectorBundle\Moodle;

use \Pfe\Bundle\CoreBundle\Entity\Module;

/**
 * Description of ThemeCollector
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class ModuleCollectorComposant extends AbstractCollectorComposant
{

    /**
     * {@inheritdoc }
     */
    protected static $ENTITY_CLASS_NAME = "Module";

    /**
     *
     * @param array $data
     * @return Module
     */
    protected function hydrateEntity($data)
    {
        $mooc_id = intval($data['id']);
        $module = $this->doctrine->getRepository("PfeCoreBundle:Module")->findOneBy(array('mooc_id' => $mooc_id));

        if (!$module) {
            $module = new Module();
            $module->setMoocId($mooc_id);
            $this->stats["created"] ++;
        } else {
            $this->stats["updated"] ++;
        }

        $mooc_module_code = intval($data['module']);
        switch ($mooc_module_code) {
            case 7:
                $type = "feedback";
                break;
            case 9:
                $type = "forum";
                break;
            case 10:
                $type = "glossary";
                break;
            case 15:
                $type = "page";
                break;
            case 16:
                $type = "quiz";
                break;
            case 17:
                $type = "resource";
                break;
            case 20:
                $type = "url";
                break;
            case 22:
                $type = "workshop";
                break;
        }

        $module->setSectionMoocId($data['section']);
        $module->setName($data[$type . '_name']);
        $module->setType($type);

        return $module;
    }

    /**
     *
     * @param ?string $theme_label_id Select only from one theme
     * @return string The select query
     */
    protected function getQuery($theme_label_id = null)
    {
        $query = <<<EOT
            SELECT DISTINCT cm.id, cm.module, cm.section, cm.instance, feedback.name as feedback_name, forum.name as forum_name, glossary.name as glossary_name, page.name as page_name, quiz.name as quiz_name, resource.name as resource_name, url.name as url_name, workshop.name as workshop_name

            FROM (SELECT * FROM `mdl_course_modules`) cm
            LEFT JOIN `mdl_feedback` feedback ON feedback.id = cm.instance
            LEFT JOIN `mdl_forum` forum ON forum.id = cm.instance
            LEFT JOIN `mdl_glossary` glossary ON glossary.id = cm.instance
            LEFT JOIN `mdl_page` page ON page.id = cm.instance
            LEFT JOIN `mdl_quiz` quiz ON quiz.id = cm.instance
            LEFT JOIN `mdl_resource` resource ON resource.id = cm.instance
            LEFT JOIN `mdl_url` url ON url.id = cm.instance
            LEFT JOIN `mdl_workshop`as workshop ON workshop.id = cm.instance
            WHERE cm.visible=1 AND cm.module IN (7, 9, 10, 15, 16, 17, 20, 22)
EOT;
        if ($theme_label_id) {
            $query .= " AND cm.course=:" . $theme_label_id;
        }

        $query .= " ORDER BY cm.module";

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
        $query = <<<EOT
            SELECT DISTINCT cm.id, cm.module, cm.section, cm.instance, feedback.name as feedback_name, forum.name as forum_name, glossary.name as glossary_name, page.name as page_name, quiz.name as quiz_name, resource.name as resource_name, url.name as url_name, workshop.name as workshop_name

            FROM (SELECT * FROM `mdl_course_modules`) cm
            LEFT JOIN `mdl_feedback` feedback ON feedback.id = cm.instance
            LEFT JOIN `mdl_forum` forum ON forum.id = cm.instance
            LEFT JOIN `mdl_glossary` glossary ON glossary.id = cm.instance
            LEFT JOIN `mdl_page` page ON page.id = cm.instance
            LEFT JOIN `mdl_quiz` quiz ON quiz.id = cm.instance
            LEFT JOIN `mdl_resource` resource ON resource.id = cm.instance
            LEFT JOIN `mdl_url` url ON url.id = cm.instance
            LEFT JOIN `mdl_workshop`as workshop ON workshop.id = cm.instance

            WHERE cm.visible=1 AND cm.module IN (7, 9, 10, 15, 16, 17, 20, 22) AND cm.id=:mooc_id
EOT;
        if ($theme_label_id) {
            $query .= " AND cm.course=:" . $theme_label_id;
        }

        $query .= " ORDER BY cm.module";

        return $query;
    }

}
