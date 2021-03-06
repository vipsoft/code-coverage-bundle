<?php
/**
 * Code Coverage Bundle
 *
 * @copyright 2013 Anthon Pang
 * @license BSD-2-Clause
 */

namespace VIPSoft\CodeCoverageBundle;

use VIPSoft\TestCase;

/**
 * Bundle test
 *
 * @group Unit
 */
class VIPSoftCodeCoverageBundleTest extends TestCase
{
    public function testGetNamespace()
    {
        $bundle = new VIPSoftCodeCoverageBundle();

        $this->assertEquals('VIPSoft\\CodeCoverageBundle', $bundle->getNamespace());
    }

    public function testGetName()
    {
        $bundle = new VIPSoftCodeCoverageBundle();

        $this->assertEquals('VIPSoftCodeCoverageBundle', $bundle->getName());
    }

    public function testBuild()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $container->expects($this->once())
                  ->method('addCompilerPass');

        $bundle = new VIPSoftCodeCoverageBundle();
        $bundle->build($container);
    }
}
