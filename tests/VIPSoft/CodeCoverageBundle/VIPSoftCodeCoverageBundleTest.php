<?php
/**
 * Code Coverage Controller
 *
 * @copyright 2013 Anthon Pang
 * @license BSD-2-Clause
 */

namespace VIPSoft\CodeCoverageBundle;

use VIPSoft\TestCase;

/**
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
}
