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
     * Constructor
     *
     * @param \VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository $repository
     */
    public function __construct(CodeCoverageRepository $repository)
    {
        $this->repository = $repository;
    }
     
    /**
     * Start collecting at the beginning of a request
     *
     * @parma \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $repository = $this->repository;

        if ( ! $repository->isEnabled()) {
            return;
        }

        xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);

        register_shutdown_function(function () use ($repository) {
            $coverage = xdebug_get_code_coverage();

            xdebug_stop_code_coverage(true);

            $repository->addCoverage($coverage);
        });
    }
}
