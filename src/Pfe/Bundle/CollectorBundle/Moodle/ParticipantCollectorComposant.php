<?php

namespace Pfe\Bundle\CollectorBundle\Moodle;

use Pfe\Bundle\CoreBundle\Entity\Participant;
use Pfe\Bundle\CoreBundle\Entity\Etudiant;
use Pfe\Bundle\CoreBundle\Entity\Apprenant;
use Pfe\Bundle\CoreBundle\Entity\Staff;

/**
 * Description of ThemeCollector
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class ParticipantCollectorComposant extends AbstractCollectorComposant
{

    /**
     * {@inheritdoc }
     */
    protected static $ENTITY_CLASS_NAME = "Participant";

    /**
     *
     * @param array $data
     * @return Participant
     */
    protected function hydrateEntity($data)
    {
        $mooc_id = intval($data['id']);
        $participant = $this->doctrine->getRepository("PfeCoreBundle:Participant")->findOneBy(array('mooc_id' => $mooc_id));

        if (!$participant) {
            if ($data['enrol'] === 'cohort' && $data['shortname'] === 'student') {
                $participant = new Etudiant();
            } elseif ($data['enrol'] === 'self' && $data['shortname'] === 'student') {
                $participant = new Apprenant();
            } elseif ($data['shortname'] !== 'student') {
                $participant = new Staff();
            } else {
                $participant = new Apprenant();
            }

            $participant->setMoocId($mooc_id);
            $this->stats["created"] ++;
        } else {
            $this->stats["updated"] ++;
        }

        $participant->setName($data['firstname'] . " " . $data['lastname']);
        $participant->setEmail($data['email']);
        $participant->setCountryCode($data['country']);
        $participant->setCity($data['city']);

        $last_access = new \DateTime();
        $last_access->setTimestamp($data['timeaccess']);
        $participant->setLast_access($last_access);

        return $participant;
    }

    /**
     *
     * @param ?string $theme_label_id Select only from one theme
     * @return string The select query
     */
    protected function getQuery($theme_label_id = null)
    {
        $query = <<<EOT
            SELECT u.id, u.auth, u.username, u.firstname, u.lastname, u.email, u.city, u.country, u.lang, e.enrol, r.shortname, ul.timeaccess
            FROM mdl_user u
            JOIN mdl_enrol e
            JOIN mdl_user_enrolments ue ON ue.userid=u.id AND ue.enrolid=e.id
            LEFT JOIN mdl_context c ON c.contextlevel=50 AND c.instanceid=e.courseid
            LEFT JOIN mdl_role_assignments ra ON  ra.userid=u.id AND ra.contextid=c.id
            LEFT JOIN mdl_role r ON ra.roleid=r.id
            LEFT JOIN mdl_user_lastaccess ul ON ul.userid=u.id AND ul.courseid=e.courseid
EOT;

        if ($theme_label_id) {
            $query .= " WHERE e.courseid=:" . $theme_label_id;
        }

        $query .= " GROUP BY u.id";

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
            SELECT u.id, u.auth, u.username, u.firstname, u.lastname, u.email, u.city, u.country, u.lang, e.enrol, r.shortname, ul.timeaccess
            FROM mdl_user u
            JOIN mdl_enrol e
            JOIN mdl_user_enrolments ue ON ue.userid=u.id AND ue.enrolid=e.id
            LEFT JOIN mdl_context c ON c.contextlevel=50 AND c.instanceid=e.courseid
            LEFT JOIN mdl_role_assignments ra ON  ra.userid=u.id AND ra.contextid=c.id
            LEFT JOIN mdl_role r ON ra.roleid=r.id
            LEFT JOIN mdl_user_lastaccess ul ON ul.userid=u.id AND ul.courseid=e.courseid
            WHERE u.id=:mooc_id
EOT;

        if ($theme_label_id) {
            $query .= " AND e.courseid=:" . $theme_label_id;
        }

        $query .= " GROUP BY u.id";

        return $query;
    }

}
