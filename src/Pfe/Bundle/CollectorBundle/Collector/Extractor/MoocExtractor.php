<?php

namespace Pfe\Bundle\CollectorBundle\Collector\Extractor;

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

    public function __construct(\Doctrine\DBAL\Connection $connection) {
        $this->connection = $connection;
        if (!$this->connection->isConnected()) {
            $this->connection->connect();
        }
    }

    public function isConnected() {
        return $this->connection->isConnected();
    }

    public function mysqlImportCmd($output, $path) {
        $username = " -u " . $this->connection->getUsername();
        $password = empty($this->connection->getPassword()) ? "" : " -p " . $this->connection->getPassword();

        $cmd = "mysql" . $username . $password . " " . $this->connection->getDatabase() . " < " . $path;

        $output->writeln("Executing mysql import...");
        exec($cmd);
        $output->writeln("Mysql import done");

        return;
    }

    public function extract($output, $course_id) {
        $this->statement = $this->connection->prepare($this->getQuery('course_id'));
        $this->statement->bindParam('course_id', $course_id);

        if (!$this->statement->execute()) {
            $output->writeln("<error>Unable to execute the query");
        }
    }

    private function getQuery($course_label) {
        $query = <<<EOT
            SELECT u.id, u.auth, u.username, u.firstname, u.lastname, u.email, u.city, u.country, u.lang, e.enrol, r.shortname, ul.timeaccess
            FROM mdl_user u
            JOIN mdl_enrol e ON e.courseid=:{$course_label}
            JOIN mdl_user_enrolments ue ON ue.userid=u.id AND ue.enrolid=e.id
            LEFT JOIN mdl_context c ON c.contextlevel=50 AND c.instanceid=:{$course_label}
            LEFT JOIN mdl_role_assignments ra ON  ra.userid=u.id AND ra.contextid=c.id
            LEFT JOIN mdl_role r ON ra.roleid=r.id
            LEFT JOIN mdl_user_lastaccess ul ON ul.userid=u.id AND ul.courseid=:{$course_label}
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

}
