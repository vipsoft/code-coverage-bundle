<?php
/**
 * Code Coverage Bundle
 *
 * @copyright 2013 Anthon Pang
 * @license BSD-2-Clause
 */

namespace VIPSoft\CodeCoverageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use VIPSoft\CodeCoverageBundle\DependencyInjection\Compiler\FactoryPass;

/**
 * Code coverage bundle
 *
 * @author Anthon Pang <apang@softwaredevelopment.ca>
 */
class VIPSoftCodeCoverageBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FactoryPass());
    }
}
