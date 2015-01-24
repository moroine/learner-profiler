<?php

namespace Pfe\Bundle\CollectorBundle\Moodle;

use \Pfe\Bundle\CoreBundle\Entity\Action;

/**
 * Description of ThemeCollector
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class ActionCollectorComposant extends AbstractCollectorComposant
{

    /**
     * {@inheritdoc }
     */
    protected static $ENTITY_CLASS_NAME = "Action";

    /**
     *
     * @param array $data
     * @return Action
     */
    protected function hydrateEntity($data)
    {
        $mooc_id = intval($data['id']);
        $action = $this->doctrine->getRepository("PfeCoreBundle:Action")->findOneBy(array('mooc_id' => $mooc_id));

        if (!$action) {
            $action = new Action();
            $action->setMoocId($mooc_id);
            $this->stats["created"] ++;
        } else {
            $this->stats["updated"] ++;
        }

        $date = new \DateTime();
        $date->setTimestamp(trim($data['time']));
        $action->setDatetime($date);
        $action->setMoocModuleId($data['cmid']);
        $action->setMoocUserId($data['userid']);
        $action->setIp($data['ip']);

        return $action;
    }

    /**
     *
     * @param ?string $theme_label_id Select only from one theme
     * @return string The select query
     */
    protected function getQuery($theme_label_id = null)
    {
        $query = <<<EOT
            SELECT l.id, l.time, l.userid, l.ip, l.cmid, l.action
            FROM mdl_log l
EOT;

        if ($theme_label_id) {
            $query .= " WHERE l.course=:" . $theme_label_id;
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
        $query = <<<EOT
            SELECT l.id, l.time, l.userid, l.ip, l.cmid, l.action
            FROM mdl_log l
            WHERE l.id=:mooc_id
EOT;

        if ($theme_label_id) {
            $query .= " AND l.course=:" . $theme_label_id;
        }

        return $query;
    }

}
