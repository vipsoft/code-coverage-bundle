<?php
/**
 * Code Coverage Controller
 *
 * @copyright 2013 Anthon Pang
 * @license BSD-2-Clause
 */

namespace VIPSoft\CodeCoverageBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

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
     * Constructor
     *
     * @param \VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    /**
     * Start code coverage
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        // this setting wasn't introduced until Xdebug 2.2
        $ini = ini_get_all('xdebug', false);

        if (isset($ini['xdebug.coverage_enable']) && ! $ini['xdebug.coverage_enable']) {
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
