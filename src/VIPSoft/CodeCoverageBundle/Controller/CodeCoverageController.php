<?php
/**
 * Code Coverage Controller
 *
 * @copyright 2013 Anthon Pang
 * @license BSD-2-Clause
 */

namespace VIPSoft\CodeCoverageBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository;
use VIPSoft\CodeCoverageCommon\Driver;

/**
 * Code coverage controller
 *
 * @author Anthon Pang <apang@softwaredevelopment.ca>
 */
class CodeCoverageController
{
    /**
     * @var \VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository
     */
    private $repository;

    /**
     * @var \VIPSoft\CodeCoverageCommon\Driver
     */
    private $driver;

    /**
     * Constructor
     *
     * @param \VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository $repository
     * @param \VIPSoft\CodeCoverageCommon\Driver                         $driver
     */
    public function __construct(CodeCoverageRepository $repository, Driver $driver = null)
    {
        $this->repository = $repository;
        $this->driver     = $driver;
    }

    /**
     * Start code coverage
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        if ( ! $this->driver) {
            return new Response('', 503); // Service Unavailable
        }

        if ( ! $this->repository->initialize()) {
            return new Response('', 500); // Internal Server Error
        }

        return new Response();
    }

    /**
     * Get code coverage (output raw coverage data as JSON)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function readAction()
    {
        $coverage = $this->repository->getCoverage();

        return new Response(json_encode($coverage), 200, array('content-type' => 'application/json'));
    }

    /**
     * Stop code coverage
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction()
    {
        $this->repository->drop();

        return new Response();
    }
}
