<?php
/**
 * Code Coverage Controller
 *
 * @copyright 2013 Anthon Pang
 * @license BSD-2-Clause
 */

namespace VIPSoft\CodeCoverageBundle\Controller;

use VIPSoft\TestCase;

/**
 * Controller test
 *
 * @group Unit
 */
class CodeCoverageControllerTest extends TestCase
{
    public function testCreateActionWhenXdebugDisabled()
    {
        $repository = $this->getMockBuilder('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository')
                           ->disableOriginalConstructor()
                           ->getMock();

        $controller = new CodeCoverageController($repository, null);

        $response = $controller->createAction();

        $this->assertEquals(503, $response->getStatusCode());
    }

    public function testCreateActionWhenRepositoryFails()
    {
        $repository = $this->getMockBuilder('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository')
                           ->disableOriginalConstructor()
                           ->getMock();
        $repository->expects($this->once())
                   ->method('initialize')
                   ->will($this->returnValue(false));

        $driver = $this->getMock('VIPSoft\CodeCoverageCommon\Driver');

        $controller = new CodeCoverageController($repository, $driver);

        $response = $controller->createAction();

        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testCreateAction()
    {
        $repository = $this->getMockBuilder('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository')
                           ->disableOriginalConstructor()
                           ->getMock();
        $repository->expects($this->once())
                   ->method('initialize')
                   ->will($this->returnValue(true));

        $driver = $this->getMock('VIPSoft\CodeCoverageCommon\Driver');

        $controller = new CodeCoverageController($repository, $driver);

        $response = $controller->createAction();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testReadAction()
    {
        $repository = $this->getMockBuilder('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository')
                           ->disableOriginalConstructor()
                           ->getMock();
        $repository->expects($this->once())
                   ->method('getCoverage')
                   ->will($this->returnValue(array('X'=>array(1 => -1))));

        $driver = $this->getMock('VIPSoft\CodeCoverageCommon\Driver');

        $controller = new CodeCoverageController($repository, $driver);

        $response = $controller->readAction();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertEquals('{"X":{"1":-1}}', $response->getContent());
    }

    public function testDeleteAction()
    {
        $repository = $this->getMockBuilder('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository')
                           ->disableOriginalConstructor()
                           ->getMock();
        $repository->expects($this->once())
                   ->method('drop');

        $driver = $this->getMock('VIPSoft\CodeCoverageCommon\Driver');

        $controller = new CodeCoverageController($repository, $driver);

        $response = $controller->deleteAction();

        $this->assertEquals(200, $response->getStatusCode());
    }
}
