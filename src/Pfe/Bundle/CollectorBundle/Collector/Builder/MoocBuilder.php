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
    private $stats;

    public function __construct(\Doctrine\Bundle\DoctrineBundle\Registry $doctrine, \Pfe\Bundle\GPlaceApiBundle\GPlaceApi\GPlaceApi $gplace_api) {
        $this->doctrine = $doctrine;

        $this->gplace_api = $gplace_api;

        $this->stats = array();
        $this->stats['participants'] = 0;
        $this->stats['etudiants'] = 0;
        $this->stats['staffs'] = 0;
        $this->stats['apprenants'] = 0;
        $this->stats['localisations'] = 0;
    }

    /**
     *
     * @param type $data
     * @return \Pfe\Bundle\DataBundle\Entity\Participant
     */
    public function buildParticipant(OutputInterface $output, $data) {
        if ($data['auth'] === 'shibboleth' || ($data['enrol'] === 'manual' || $data['enrol'] === 'cohort') && $data['shortname'] === 'student') {
            $participant = new \Pfe\Bundle\DataBundle\Entity\Etudiant($data['firstname'] . " " . $data['lastname'], $data['email'], $data['lang'], $data['timeaccess']);
            $this->stats['etudiants'] ++;
        } elseif ($data['auth'] === 'email' && $data['enrol'] === 'self' && $data['shortname'] === 'student') {
            $participant = new \Pfe\Bundle\DataBundle\Entity\Apprenant($data['firstname'] . " " . $data['lastname'], $data['email'], $data['lang'], $data['timeaccess']);
            $this->stats['apprenants'] ++;
        } elseif (($data['enrol'] === 'manual' || $data['enrol'] === 'cohort') && $data['shortname'] !== 'student') {
            $participant = new \Pfe\Bundle\DataBundle\Entity\Staff($data['firstname'] . " " . $data['lastname'], $data['email'], $data['lang'], $data['timeaccess']);
            $this->stats['staffs'] ++;
        } else {

            $participant = new \Pfe\Bundle\DataBundle\Entity\Participant($data['firstname'] . " " . $data['lastname'], $data['email'], $data['lang'], $data['timeaccess']);
        }
        $this->stats['participants'] ++;

        $name = trim($data['firstname'] . " " . $data['lastname']);
        $participant->setName($name);

        $email = strtolower(trim($data['email']));
        $participant->setEmail($email);

        $last_access = new \DateTime();
        $last_access->setTimestamp($data['timeaccess']);
        $participant->setLast_access($last_access);

        $lang = trim(strtolower($data['lang']));
        $participant->setLang($lang);

        $country = trim($data['country']);
        $city = trim($data['city']);

        $localisation = $this->getLocalisation($country, $city, $output);
        $participant->setHome($localisation);

        $this->doctrine->getManager()->persist($participant);

        return $participant;
    }

    public function saveChanges() {
        $this->doctrine->getManager()->flush();
    }

    public function getStats() {
        return $this->stats['participants'] . " participants ajoutés: " . $this->stats['apprenants'] . " apprenants + " . $this->stats['etudiants'] . " étudiants + " . $this->stats['staffs'] . " staffs\n" . $this->stats['localisations'] . " localisations";
    }

    private function getLocalisation($country, $city, $output) {
        $repo = $this->doctrine->getRepository("PfeDataBundle:Localisation");

        $localisation = $repo->findOneBy(array("state" => $country, "city" => $city));

        if (empty($localisation)) {
            $localisation = new \Pfe\Bundle\DataBundle\Entity\Localisation($country, $city);

            if (!empty($country) && !empty($city)) {
                $gplaces = $this->gplace_api->searchLocality($city, $country);
            } elseif (!empty($country)) {
                $gplaces = $this->gplace_api->searchState($country);
            } else {
                return null;
            }

            switch (count($gplaces)) {
                case 0:
                    $output->writeln('<error>Empty response for ' . $country . ' - ' . $city . '</error>');
                    return null;
                case 1:
                    break;
                default:
                    $output->writeln('<error>' . count($gplaces) . ' results for ' . $country . ' - ' . $city . '</error>');
                    break;
            }

            $gplace = $gplaces[0];
            $localisation->setLatitude($gplace->geometry->location->lat);
            $localisation->setLongitude($gplace->geometry->location->lng);
            $localisation->getG_place_id($gplace->place_id);
            $localisation->getG_address($gplace->formatted_address);

            $this->doctrine->getManager()->persist($localisation);
            $this->doctrine->getManager()->flush();
            $this->stats['localisations'] ++;
        }

        return $localisation;
    }

}
