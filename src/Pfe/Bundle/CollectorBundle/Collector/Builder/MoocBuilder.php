<?php

namespace Pfe\Bundle\CollectorBundle\Collector\Builder;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of MoocBuilder
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class MoocBuilder {

    /**
     *
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    /**
     *
     * @var \Pfe\Bundle\GPlaceApiBundle\GPlaceApi\GPlaceApi
     */
    private $gplace_api;

    /**
     *
     * @var \Pfe\Bundle\GeonamesApiBundle\GeonamesApi\GeonamesApi
     */
    private $geonames_api;

    /**
     *
     * @var array
     */
    private $stats;
    private $n_managed;

    public function __construct(\Doctrine\Bundle\DoctrineBundle\Registry $doctrine, \Pfe\Bundle\GPlaceApiBundle\GPlaceApi\GPlaceApi $gplace_api, \Pfe\Bundle\GeonamesApiBundle\GeonamesApi\GeonamesApi $geoname_api) {
        $this->doctrine = $doctrine;

        $this->gplace_api = $gplace_api;
        $this->geonames_api = $geoname_api;

        $this->stats = array();
        $this->stats['participants'] = 0;
        $this->stats['etudiants'] = 0;
        $this->stats['staffs'] = 0;
        $this->stats['apprenants'] = 0;
        $this->stats['localisations'] = 0;
        $this->stats['theme'] = 0;
        $this->stats['section'] = 0;
        $this->stats['module'] = 0;
        $this->stats['action'] = 0;

        $this->n_managed = 0;
    }

    /**
     *
     * @param type $data
     * @return \Pfe\Bundle\CoreBundle\Entity\Theme
     */
    public function buildTheme(OutputInterface $output, $data) {
        $id = intval($data['id']);
        $name = trim($data['fullname']);

        $theme = new \Pfe\Bundle\CoreBundle\Entity\Theme();

        $theme->setName($name);
        $theme->setCourseId($id);

        $this->doctrine->getManager()->persist($theme);
        $this->stats['theme'] ++;

        return $theme;
    }

    /**
     *
     * @param type $data
     * @return \Pfe\Bundle\CoreBundle\Entity\Section
     */
    public function buildSection(OutputInterface $output, $data, \Pfe\Bundle\CoreBundle\Entity\Theme $theme = null) {
        $name = trim($data['name']);
        $order = intval($data['section']);

        if ($theme === null) {
            $course_id = intval($data['course']);
            $theme = $this->doctrine->getRepository("PfeCoreBundle:Theme")->findOneBy(array('course_id' => $course_id));
        }

        $section = new \Pfe\Bundle\CoreBundle\Entity\Section($name, $order, 7, $theme);

        $section->setMoocId(intval($data['id']));

        $this->stats['section'] ++;

        $this->doctrine->getManager()->persist($section);

        return $section;
    }

    /**
     *
     * @param array $data
     * @param \Pfe\Bundle\CoreBundle\Entity\Section[]|null $sections
     * @return \Pfe\Bundle\CoreBundle\Entity\Module
     */
    public function buildModule(OutputInterface $output, $data, $sections = null) {
        /**
         * @var \Pfe\Bundle\CoreBundle\Entity\Section[] $sections
         */
        $mooc_id = intval($data['id']);
        $module_type = intval($data['module']);
        switch ($module_type) {
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
        $name = trim($data[$type . '_name']);
        $section_order = intval($data['section']);

        $module = null;
        if ($sections !== null) {
            if (array_key_exists($section_order, $sections)) {
                $module = new \Pfe\Bundle\CoreBundle\Entity\Module($name, $type, $sections[$section_order]);
                $module->setMoocId($mooc_id);
                $this->stats['module'] ++;
            }
        } else {
            $section = $this->doctrine->getRepository("PfeCoreBundle:Section")->findBy(array("order" => $section_order));
            $module->setMoocId($mooc_id);
            $this->stats['module'] ++;
        }

        $this->doctrine->getManager()->persist($module);

        return $module;
    }

    /**
     *
     * @param type $data
     * @return \Pfe\Bundle\CoreBundle\Entity\Participant
     */
    public function buildParticipant(OutputInterface $output, $data) {

        $email = strtolower(trim($data['email']));

        $participant = $this->doctrine->getManager()->getRepository("PfeCoreBundle:Participant")->findOneByEmail($email);

        if ($participant === null) {
            if ($data['auth'] === 'shibboleth' || ($data['enrol'] === 'manual' || $data['enrol'] === 'cohort') && $data['shortname'] === 'student') {
                $participant = new \Pfe\Bundle\CoreBundle\Entity\Etudiant($data['firstname'] . " " . $data['lastname'], $data['email'], $data['lang'], $data['timeaccess']);
                $this->stats['etudiants'] ++;
            } elseif ($data['auth'] === 'email' && $data['enrol'] === 'self' && $data['shortname'] === 'student') {
                $participant = new \Pfe\Bundle\CoreBundle\Entity\Apprenant($data['firstname'] . " " . $data['lastname'], $data['email'], $data['lang'], $data['timeaccess']);
                $this->stats['apprenants'] ++;
            } elseif (($data['enrol'] === 'manual' || $data['enrol'] === 'cohort') && $data['shortname'] !== 'student') {
                $participant = new \Pfe\Bundle\CoreBundle\Entity\Staff($data['firstname'] . " " . $data['lastname'], $data['email'], $data['lang'], $data['timeaccess']);
                $this->stats['staffs'] ++;
            } else {

                $participant = new \Pfe\Bundle\CoreBundle\Entity\Participant($data['firstname'] . " " . $data['lastname'], $data['email'], $data['lang'], $data['timeaccess']);
            }

            $this->stats['participants'] ++;
        }

        $participant->setEmail($email);

        $mooc_id = intval($data['id']);
        $participant->setMoocId($mooc_id);

        $name = trim($data['firstname'] . " " . $data['lastname']);
        $participant->setName($name);

        $last_access = new \DateTime();
        $last_access->setTimestamp($data['timeaccess']);
        $participant->setLast_access($last_access);

        $lang = trim(strtolower($data['lang']));
        $participant->setLang($lang);

        $countryCode = trim($data['country']);
        $countryInfos = (empty($countryCode)) ? null : $this->getCountryInformation($output, $countryCode);

        $city = trim($data['city']);

        $localisation = $this->getLocalisation($countryInfos, $city, $output);
        $participant->setHome($localisation);

        $this->doctrine->getManager()->persist($participant);

        return $participant;
    }

    /**
     *
     * @param OutputInterface $output
     * @param \Pfe\Bundle\CoreBundle\Entity\Module[] $modules
     * @param \Pfe\Bundle\CoreBundle\Entity\Participant[] $participants
     * @param array $data
     */
    public function buildAction(OutputInterface $output, $modules, $participants, $data) {
        $time = "@" . trim($data['time']);
        $userid = intval($data['userid']);
        $ip = trim($data['ip']);
        $mooc_mod_id = intval($data['cmid']);
        $type = trim($data['action']);


        $action = new \Pfe\Bundle\CoreBundle\Entity\Action(new \DateTime($time));

        if (array_key_exists($userid, $participants)) {
            $participant = $this->doctrine->getManager()->merge($participants[$userid]);
            $action->setParticipant($participant);
        }

        if (array_key_exists($mooc_mod_id, $modules)) {
            $participant = $this->doctrine->getManager()->merge($modules[$mooc_mod_id]);
            $action->setParticipant($participant);
        }


        $action->setIp($ip);
        $action->setType($type);

        $this->stats['action'] ++;

        $this->doctrine->getManager()->persist($action);

        $this->n_managed ++;
        if ($this->n_managed > 1000) {
            $this->n_managed = 0;
            $this->saveChanges();
        }

        return $action;
    }

    public function saveChanges() {
        $this->doctrine->getManager()->flush();
        $this->doctrine->getManager()->clear();
    }

    public function getStats() {
        return $this->stats['participants'] . " participants ajoutés: " . $this->stats['apprenants'] . " apprenants + " . $this->stats['etudiants'] . " étudiants + " . $this->stats['staffs'] . " staffs\n" . $this->stats['localisations'] . " localisations\n" . $this->stats['theme'] . " theme + " . $this->stats['section'] . ' sections + ' . $this->stats['module'] . ' modules\n' . $this->stats['action'] . " actions";
    }

    private function getLocalisation($countryInfos, $city, OutputInterface $output) {
        $countryName = (empty($countryInfos)) ? null : $countryInfos->countryName;

        $repo = $this->doctrine->getRepository("PfeCoreBundle:Localisation");

        $localisation = $repo->findOneBy(array("state" => $countryName, "city" => $city));

        if (empty($localisation)) {
            $localisation = new \Pfe\Bundle\CoreBundle\Entity\Localisation($countryName, $city);

            if (!empty($countryName) && !empty($city) && $city !== $countryName) {
                $gplaces = $this->gplace_api->searchLocality($city, $countryName);
            } elseif (!empty($countryName)) {
                $gplaces = $this->gplace_api->searchState($countryName);
            } else {
                return null;
            }

            switch (count($gplaces)) {
                case 0:
                    $output->writeln('<error>Empty response for ' . $countryName . ' - ' . $city . '</error>');
                    return null;
                case 1:
                    break;
                default:
                    $output->writeln('<error>' . count($gplaces) . ' results for ' . $countryName . ' - ' . $city . '</error>');
                    break;
            }

            $gplace = $gplaces[0];

            $localisation->setLatitude($gplace->geometry->location->lat);
            $localisation->setLongitude($gplace->geometry->location->lng);
            $localisation->getG_place_id($gplace->place_id);
            $localisation->getG_address($gplace->formatted_address);

            if (!empty($countryInfos->fipsCode)) {
                $localisation->setFips($countryInfos->fipsCode);
            }

            if (!empty($countryInfos->countryCode)) {
                $localisation->setIsoAlpha2($countryInfos->countryCode);
            }

            if (!empty($countryInfos->isoAlpha3)) {
                $localisation->setIsoAlpha3($countryInfos->isoAlpha3);
            }

            $this->doctrine->getManager()->persist($localisation);
            $this->doctrine->getManager()->flush();

            $this->stats['localisations'] ++;
        }

        return $localisation;
    }

    private function getCountryInformation(OutputInterface $output, $countryCode) {
        $results = $this->geonames_api->searchByCountryCode($countryCode);

        if (count($results) === 0) {
            $output->writeln("<error>No country found for code " . $countryCode . "</error>");

            return null;
        }

        if (count($results) > 1) {
            $output->writeln("<error>" . count($results) . " country found for code " . $countryCode . "</error>");
        }

        return $results[0];
    }

}
