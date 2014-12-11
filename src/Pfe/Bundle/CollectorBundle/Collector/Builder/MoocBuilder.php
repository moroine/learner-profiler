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
    private $stats;

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
    }

    /**
     *
     * @param type $data
     * @return \Pfe\Bundle\DataBundle\Entity\Participant
     */
    public function buildParticipant(OutputInterface $output, $data) {

        $email = strtolower(trim($data['email']));

        $participant = $this->doctrine->getManager()->getRepository("PfeDataBundle:Participant")->findOneByEmail($email);

        if ($participant === null) {
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
        }

        $participant->setEmail($email);

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

    public function saveChanges() {
        $this->doctrine->getManager()->flush();
    }

    public function getStats() {
        return $this->stats['participants'] . " participants ajoutés: " . $this->stats['apprenants'] . " apprenants + " . $this->stats['etudiants'] . " étudiants + " . $this->stats['staffs'] . " staffs\n" . $this->stats['localisations'] . " localisations";
    }

    private function getLocalisation($countryInfos, $city, $output) {
        $countryName = (empty($countryInfos)) ? null : $countryInfos->countryName;

        $repo = $this->doctrine->getRepository("PfeDataBundle:Localisation");

        $localisation = $repo->findOneBy(array("state" => $countryName, "city" => $city));

        if (empty($localisation)) {
            $localisation = new \Pfe\Bundle\DataBundle\Entity\Localisation($countryName, $city);

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

    private function getCountryInformation($output, $countryCode) {
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
