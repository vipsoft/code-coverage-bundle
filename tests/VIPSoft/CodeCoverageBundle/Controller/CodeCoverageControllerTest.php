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
 * @group Unit
 */
class CodeCoverageControllerTest extends TestCase
{
    public function testCreateActionWhenXdebugDisabled()
    {
        $this->getMockFunction('ini_get_all', function () { return array('xdebug.coverage_enable' => 0); });

        $repository = $this->getMock('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository');

        $controller = new CodeCoverageController($repository);

        $response = $controller->createAction();

        $this->assertEquals(503, $response->getStatusCode());
    }

    public function testCreateActionWhenRepositoryFails()
    {
        $this->getMockFunction('ini_get_all', function () { return true; });

        $repository = $this->getMock('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository');
        $repository->expects($this->once())
                   ->method('initialize')
                   ->will($this->returnValue(false));

        $controller = new CodeCoverageController($repository);

        $response = $controller->createAction();

        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->getMockFunction('ini_get_all', function () { return true; });

        $repository = $this->getMock('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository');
        $repository->expects($this->once())
                   ->method('initialize')
                   ->will($this->returnValue(true));

        $controller = new CodeCoverageController($repository);

        $response = $controller->createAction();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testReadAction()
    {
        $repository = $this->getMock('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository');
        $repository->expects($this->once())
                   ->method('getCoverage')
                   ->will($this->returnValue(array('X'=>array(1 => -1))));

        $controller = new CodeCoverageController($repository);

        $response = $controller->readAction();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertEquals('{"X":{"1":-1}}', $response->getContent());
    }

    public function testDeleteAction()
    {
        $repository = $this->getMock('VIPSoft\CodeCoverageBundle\Service\CodeCoverageRepository');
        $repository->expects($this->once())
                   ->method('drop');

        $controller = new CodeCoverageController($repository);

        $response = $controller->deleteAction();

        $this->assertEquals(200, $response->getStatusCode());
    }
}
