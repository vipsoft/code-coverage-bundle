<?php
/**
 * Bundle Configuration Test
 *
 * @copyright 2013 Anthon Pang
 * @license BSD-2-Clause
 */

namespace VIPSoft\CodeCoverageBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use VIPSoft\TestCase;

/**
 * @group Functional
 */
class VIPSoftCodeCoverageExtensionTest extends TestCase
{
    public function testLoad()
    {
        $container = new ContainerBuilder();

        $extension = new VIPSoftCodeCoverageExtension();
        $extension->load(array(), $container);

        $parameters = $container->getParameterBag()->all();
        $this->assertTrue(isset($parameters['vipsoft.code_coverage.controller.code_coverage.class']));
        $this->assertTrue(isset($parameters['vipsoft.code_coverage.service.repository.class']));

        $serviceIds = $container->getServiceIds();
        $this->assertTrue(in_array('vipsoft.code_coverage.controller.code_coverage', $serviceIds));
        $this->assertTrue(in_array('vipsoft.code_coverage.service.repository', $serviceIds));
    }
}
