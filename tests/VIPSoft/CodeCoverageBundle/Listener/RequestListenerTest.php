<?php
/**
 * Request Listener
 *
 * @copyright 2013 Anthon Pang
 * @license BSD-2-Clause
 */

namespace VIPSoft\CodeCoverageBundle\Listener;

use VIPSoft\TestCase;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Listener test
 *
 * @group Unit
 */
class RequestListenerTest extends TestCase
{
    public function testOnKernelRequestWhenSubRequest()
    {
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')
                      ->disableOriginalConstructor()
                      ->getMock();
        $event->expects($this->once())
              ->method('getRequestType')
              ->will($this->returnValue(HttpKernelInterface::SUB_REQUEST));

        $repository = $this->getMock('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository');

        $driver = $this->getMock('VIPSoft\CodeCoverageCommon\Driver');
        $driver->expects($this->never())
               ->method('start');

        $listener = new RequestListener($repository, $driver);
        $listener->onKernelRequest($event);
    }

    public function testOnKernelRequestWhenRepositoryDisabled()
    {
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')
                      ->disableOriginalConstructor()
                      ->getMock();
        $event->expects($this->once())
              ->method('getRequestType')
              ->will($this->returnValue(HttpKernelInterface::MASTER_REQUEST));

        $repository = $this->getMock('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository');
        $repository->expects($this->once())
                   ->method('isEnabled')
                   ->will($this->returnValue(false));

        $driver = $this->getMock('VIPSoft\CodeCoverageCommon\Driver');
        $driver->expects($this->never())
               ->method('start');

        $listener = new RequestListener($repository, $driver);
        $listener->onKernelRequest($event);
    }

    public function testOnKernelRequest()
    {
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')
                      ->disableOriginalConstructor()
                      ->getMock();
        $event->expects($this->once())
              ->method('getRequestType')
              ->will($this->returnValue(HttpKernelInterface::MASTER_REQUEST));

        $repository = $this->getMock('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository');
        $repository->expects($this->once())
                   ->method('isEnabled')
                   ->will($this->returnValue(true));

        $driver = $this->getMock('VIPSoft\CodeCoverageCommon\Driver');
        $driver->expects($this->once())
               ->method('start');

        $listener = new RequestListener($repository, $driver);
        $listener->onKernelRequest($event);
    }

    public function testOnKernelTerminateWhenRepositoryDisabled()
    {
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\PostResponseEvent')
                      ->disableOriginalConstructor()
                      ->getMock();

        $repository = $this->getMock('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository');
        $repository->expects($this->once())
                   ->method('isEnabled')
                   ->will($this->returnValue(false));

        $driver = $this->getMock('VIPSoft\CodeCoverageCommon\Driver');
        $driver->expects($this->never())
               ->method('stop');

        $listener = new RequestListener($repository, $driver);
        $listener->onKernelTerminate($event);
    }

    public function testOnKernelTerminate()
    {
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\PostResponseEvent')
                      ->disableOriginalConstructor()
                      ->getMock();

        $repository = $this->getMock('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository');
        $repository->expects($this->once())
                   ->method('isEnabled')
                   ->will($this->returnValue(true));
        $repository->expects($this->once())
                   ->method('addCoverage');

        $driver = $this->getMock('VIPSoft\CodeCoverageCommon\Driver');
        $driver->expects($this->once())
               ->method('stop')
               ->will($this->returnValue(array()));

        $listener = new RequestListener($repository, $driver);
        $listener->onKernelTerminate($event);
    }
}
