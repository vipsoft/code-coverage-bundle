<?php
/**
 * Code Coverage Repository
 *
 * @copyright 2013 Anthon Pang
 * @license BSD-2-Clause
 */

namespace VIPSoft\CodeCoverageBundle\Service;

use VIPSoft\CodeCoverageBundle\Model\Aggregate;

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
     * @param string $databaseFile
     * @param string $sqlite
     */
    public function __construct($databaseFile = null, $sqliteClassName = '\\SQLite')
    {
        $this->databaseFile    = $databaseFile ?: __DIR__ . '/../Resources/private/coverage.dbf';
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
        $sqlite    = $this->newSQLiteInstance();
        $resultSet = $sqlite->query_array('SELECT class, counts FROM coverage');

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

    private function newSQLiteInstance()
    {
        return new $this->sqliteClassName($this->databaseFile);
    }
}
