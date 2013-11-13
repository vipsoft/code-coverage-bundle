<?php
/**
 * Code Coverage Repository
 *
 * @copyright 2013 Anthon Pang
 * @license BSD-2-Clause
 */

namespace VIPSoft\CodeCoverageBundle\Service;

use VIPSoft\CodeCoverageCommon\Model\Aggregate;

/**
 * Code coverage repository
 *
 * @author Anthon Pang <apang@softwaredevelopment.ca>
 */
class CodeCoverageRepository
{
    /**
     * @var string
     */
    private $databaseFile;

    /**
     * @var string
     */
    private $sqliteClassName;

    /**
     * Constructor
     *
     * @param array  $databaseConfig
     * @param string $databaseDirectory
     * @param string $sqliteClassName
     */
    public function __construct(array $databaseConfig, $databaseDirectory, $sqliteClassName = '\SQLite3')
    {
        $this->databaseFile    = $databaseDirectory . '/' . $databaseConfig['database'];
        $this->sqliteClassName = $sqliteClassName;
    }

    /**
     * Initialize
     *
     * @return boolean
     */
    public function initialize()
    {
        $this->drop();

        $sqlite = $this->newSQLiteInstance();
        $sqlite->exec('CREATE TABLE coverage (class TEXT, counts BLOB)');

        return (boolean) $sqlite;
    }

    /**
     * Is enabled?
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return file_exists($this->databaseFile);
    }

    /**
     * Insert coverage
     *
     * @param array $coverage
     */
    public function addCoverage(array $coverage)
    {
        $sqlite = $this->newSQLiteInstance();

        foreach ($coverage as $className => $counts) {
            $counts = json_encode($counts);

            $sql = "INSERT INTO coverage (class, counts) VALUES ('$className', '$counts')";
            $sqlite->exec($sql);
        }
    }

    /**
     * Get coverage
     *
     * @return array
     */
    public function getCoverage()
    {
        $aggregate = new Aggregate();
        $resultSet = array();
        $sqlite    = $this->newSQLiteInstance();
        $result    = @$sqlite->query('SELECT class, counts FROM coverage');

        if ( ! $result) {
            return array();
        }

        while ($res = $result->fetchArray(SQLITE3_ASSOC)) {
            $resultSet[] = $res;
        }

        if ($resultSet !== false) {
            foreach ($resultSet as $result) {
                $counts = json_decode($result['counts'], true);

                $aggregate->update($result['class'], $counts);
            }
        }

        return $aggregate->getCoverage();
    }

    /**
     * Delete database file
     */
    public function drop()
    {
        if (file_exists($this->databaseFile)) {
            unlink($this->databaseFile);
        }
    }

    /**
     * Instantiate SQLite3 object
     *
     * @return \SQLite3
     */
    private function newSQLiteInstance()
    {
        $instance = new $this->sqliteClassName($this->databaseFile);

        return $instance;
    }
}
