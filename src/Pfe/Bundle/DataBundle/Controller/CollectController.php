<?php

namespace Pfe\Bundle\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pfe\Bundle\DataBundle\Entity\Participant;

/**
 * @Route("/collect")
 */
class CollectController extends Controller {

    /**
     * @Route("/participant/{count}", requirements={"count" = "\d+"}, defaults={"count" = 5000}))
     * @Template()
     */
    public function participantAction($count) {

        $client = $this->container->get('pfe_services.mooc_login')->login();

        $crawler = $client->request('GET', 'http://mooc.telecom-sudparis.eu/user/index.php?contextid=2467&roleid=5&id=4&search&perpage=' . $count);

        $rows = $crawler->filter("#participants > tbody > tr");

        $em = $this->getDoctrine()->getManager();

        $repo = $this->getDoctrine()->getRepository("PfeDataBundle:Participant");

        for ($i = 0; $i < $count; $i++) {
            $row = $rows->eq($i);

            $cells = $row->filter('td');

            $name = trim($cells->eq(2)->text());
            $email = trim($cells->eq(3)->text());
            $city = trim($cells->eq(4)->text());
            $state = trim($cells->eq(5)->text());
            $lastAccess = $cells->eq(6)->text();

            if (empty($name) && empty($email) && empty($city) && empty($state) && empty($lastAccess)) {
                break;
            }

            $participant = $repo->findOneByEmail($email);

            if (empty($participant)) {
                $nested = '';
                $day = preg_filter('#' . $nested . '([0-9]+) jour.*#', '$1', $lastAccess);
                if (!empty($day)) {
                    $nested = '.* ';
                }

                $hour = preg_filter('#' . $nested . '([0-9]+) heure.*#', '$1', $lastAccess);
                if (!empty($hour)) {
                    $nested = '.* ';
                }

                $min = preg_filter('#' . $nested . '([0-9]+) min.*#', '$1', $lastAccess);
                if (!empty($min)) {
                    $nested = '.* ';
                }

                $sec = preg_filter('#' . $nested . '([0-9]+) s.*#', '$1', $lastAccess);

                $time = ((($day * 24) + $hour ) * 60 + $min ) + $sec;

                $localisation = (empty($city) || empty($state)) ? null : $this->getLocalisation($city, $state);

                $last = new \Datetime();
                $last->setTimestamp(time() - $time);

                $participant = new Participant($name, $email, $last, $localisation);

                $em->persist($participant);
            }
        }
        $em->flush();

        return array('html' => 'Done !');
    }

    private function getLocalisation($city, $state) {
        $repo = $this->getDoctrine()->getRepository("PfeDataBundle:Localisation");

        $localisation = $repo->findOneBy(array("state" => $state, "city" => $city));

        if (empty($localisation)) {
            $gplace_api = $this->container->get('pfe_services.g_place_api');

            $response = $gplace_api->searchLocality($city, $state);

            if (empty($response)) {
                return null;
            }

            $status = $response->status; //TODO: check status

            $gplaces = $response->results;

            $n = count($gplaces);

            switch ($n) {
                case 0:
                    echo('Empty response for ' . $city . ' - ' . $state . '<br />');
                    $localisation = new \Pfe\Bundle\DataBundle\Entity\Localisation($state, $city);
                    break;
                default:
                    echo($n . ' results for ' . $city . ' - ' . $state . '<br />');
                    $gplace = $gplaces[0];
                    $localisation = new \Pfe\Bundle\DataBundle\Entity\Localisation($state, $city, $gplace->geometry->location->lat, $gplace->geometry->location->lng, $gplace->place_id, $gplace->formatted_address);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($localisation);
            $em->flush();

            echo('New localisation for ' . $city . " " . $state . "<br />");
        }

        return $localisation;
    }

}
