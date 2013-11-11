<?php
/**
 * Code Coverage Request Listener
 *
 * @copyright 2013 Anthon Pang
 * @license BSD-2-Clause
 */

namespace VIPSoft\CodeCoverageBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository;
use VIPSoft\CodeCoverageCommon\Driver;

/**
 * Code coverage request listener
 *
 * @author Anthon Pang <apang@softwaredevelopment.ca>
 */
class RequestListener
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
     * Start collecting at the beginning of a request
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()
            || ! $this->driver
        ) {
            return;
        }

        $repository = $this->repository;

        if ( ! $repository->isEnabled()) {
            return;
        }

        $driver = $this->driver;
        $driver->start();

        register_shutdown_function(function () use ($repository, $driver) {
            $coverage = $driver->stop();

            $repository->addCoverage($coverage);
        });
    }
}
