<?php
/**
 * Request Listener
 *
 * @copyright 2013 Anthon Pang
 * @license BSD-2-Clause
 */

namespace VIPSoft\CodeCoverageBundle\Listener;

use VIPSoft\TestCase;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @group Unit
 */
class RequestListenerTest extends TestCase
{
    public function testOnKernelRequestWhenSubRequest()
    {
        $proxy = $this->getMock('VIPSoft\Test\FunctionProxy');
        $proxy->expects($this->never())
              ->method('invokeFunction');

        $this->getMockFunction('xdebug_start_code_coverage', $proxy);

        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')
                      ->disableOriginalConstructor()
                      ->getMock();
        $event->expects($this->once())
              ->method('getRequestType')
              ->will($this->returnValue(HttpKernelInterface::SUB_REQUEST));

        $repository = $this->getMock('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository');

        $listener = new RequestListener($repository);
        $listener->onKernelRequest($event);
    }

    public function testOnKernelRequestWhenRepositoryDisabled()
    {
        $proxy = $this->getMock('VIPSoft\Test\FunctionProxy');
        $proxy->expects($this->never())
              ->method('invokeFunction');

        $this->getMockFunction('xdebug_start_code_coverage', $proxy);

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

        $listener = new RequestListener($repository);
        $listener->onKernelRequest($event);
    }

    public function testOnKernelRequest()
    {
        $this->getMockFunction('xdebug_start_code_coverage');
        $this->getMockFunction('xdebug_stop_code_coverage');
        $this->getMockFunction('xdebug_get_code_coverage', function () { return array(); });
        $this->getMockFunction('register_shutdown_function', function ($closure) {
            call_user_func($closure);
        });

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
        $repository->expects($this->once())
                   ->method('addCoverage');

        $listener = new RequestListener($repository);
        $listener->onKernelRequest($event);
    }
}
