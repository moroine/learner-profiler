<?php

namespace Pfe\Bundle\CollectorBundle\Collector\Extractor;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of MoocExtractor
 *
 * Extract Data from Mooc Sql
 *
 * @author Moroine Bentefrit <moroine.bentefrit@gmail.com>
 */
class MoocExtractor {

    /**
     *
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     *
     * @var \Doctrine\DBAL\Driver\Statement
     */
    private $statement;

    /**
     * @var long
     */
    private $mutex;

    public function __construct(\Doctrine\DBAL\Connection $connection) {
        $this->connection = $connection;
        if (!$this->connection->isConnected()) {
            $this->connection->connect();
        }
    }

    public function isConnected() {
        return $this->connection->isConnected();
    }

    public function importSqlDb(OutputInterface $output, $path) {
        $username = " -u " . $this->connection->getUsername();
        $password = empty($this->connection->getPassword()) ? "" : " -p" . $this->connection->getPassword();

        $cmd = "mysql" . $username . $password . " " . $this->connection->getDatabase() . " < " . $path;

        $output->writeln(">>>".$cmd);
        $outs = array();
        $status;
        exec($cmd, $outs, $status);
        foreach($outs as $out){
            $output->writeln($out);
        }

        return $status;
    }

    public function extractThemeData(OutputInterface $output, $course_id) {
        $this->statement = $this->connection->prepare($this->getThemeDataQuery('course_id'));
        $this->statement->bindParam('course_id', $course_id);

        if (!$this->statement->execute()) {
            $output->writeln("<error>Unable to execute the query");
        }
    }

    public function getThemeDataQuery($course_id_label) {
        $query = "SELECT c.id, c.fullname FROM mdl_course c WHERE c.id=:" . $course_id_label;

        return $query;
    }

    public function extractSectionData(OutputInterface $output, $course_id) {
        $this->statement = $this->connection->prepare($this->getSectionDataQuery('course_id'));
        $this->statement->bindParam('course_id', $course_id);

        if (!$this->statement->execute()) {
            $output->writeln("<error>Unable to execute the query");
        }
    }

    public function getSectionDataQuery($course_id_label) {
        $query = "SELECT s.id, s.name, s.section, s.sequence, s.course FROM mdl_course_sections s WHERE s.visible=1 AND s.course=:" . $course_id_label;

        return $query;
    }

    public function extractModuleData(OutputInterface $output, $course_id) {
        $this->statement = $this->connection->prepare($this->getModuleDataQuery('course_id'));
        $this->statement->bindParam('course_id', $course_id);

        if (!$this->statement->execute()) {
            $output->writeln("<error>Unable to execute the query");
        }
    }

    public function getModuleDataQuery($course_id_label) {
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

            WHERE cm.course=:{$course_id_label} AND cm.visible=1 AND cm.module IN (7, 9, 10, 15, 16, 17, 20,22)
            ORDER BY cm.module

EOT;

        return $query;
    }

    public function extractParticipantData(OutputInterface $output, $course_id) {
        $this->statement = $this->connection->prepare($this->getParticipantDataQuery('course_id'));
        $this->statement->bindParam('course_id', $course_id);

        if (!$this->statement->execute()) {
            $output->writeln("<error>Unable to execute the query");
        }
    }

    private function getParticipantDataQuery($course_id_label) {
        $query = <<<EOT
            SELECT u.id, u.auth, u.username, u.firstname, u.lastname, u.email, u.city, u.country, u.lang, e.enrol, r.shortname, ul.timeaccess
            FROM mdl_user u
            JOIN mdl_enrol e ON e.courseid=:{$course_id_label}
            JOIN mdl_user_enrolments ue ON ue.userid=u.id AND ue.enrolid=e.id
            LEFT JOIN mdl_context c ON c.contextlevel=50 AND c.instanceid=e.courseid
            LEFT JOIN mdl_role_assignments ra ON  ra.userid=u.id AND ra.contextid=c.id
            LEFT JOIN mdl_role r ON ra.roleid=r.id
            LEFT JOIN mdl_user_lastaccess ul ON ul.userid=u.id AND ul.courseid=e.courseid
            GROUP BY u.email
EOT;
        return $query;
    }

    public function extractActionData(OutputInterface $output, $course_id) {
        $this->statement = $this->connection->prepare($this->getActionDataQuery('course_id'));
        $this->statement->bindParam('course_id', $course_id);

        if (!$this->statement->execute()) {
            $output->writeln("<error>Unable to execute the query");
        }
    }

    private function getActionDataQuery($course_id_label) {
        $query = <<<EOT
            SELECT l.id, l.time, l.userid, l.ip, l.cmid, l.action
            FROM mdl_log l
            WHERE l.course=:{$course_id_label}
EOT;
        return $query;
    }

    public function nextRow() {
        $data = $this->statement->fetch();

        if ($data) {
            return $data;
        }

        return null;
    }

    public function nextRowsPackage($size) {
        Mutex::lock($this->mutex);

        $datas = [];
        $i = 0;
        $data = $this->nextRow();
        while ($data !== null && $i < $size) {
            $datas[] = $data;
            $i++;
            $data = $this->nextRow();
        }

        Mutex::unlock($this->mutex);

        return $datas;
    }

}
