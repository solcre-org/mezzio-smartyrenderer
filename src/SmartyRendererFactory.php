<?php

/**
 * @see       https://github.com/mezzio/mezzio-twigrenderer for the canonical source repository
 * @copyright https://github.com/mezzio/mezzio-twigrenderer/blob/master/COPYRIGHT.md
 * @license   https://github.com/mezzio/mezzio-twigrenderer/blob/master/LICENSE.md New BSD License
 */

declare(strict_types = 1);

namespace SolcreMezzio\Smarty;

use Psr\Container\ContainerInterface;
use Smarty;

/**
 * Create and return a Twig template instance.
 */
class SmartyRendererFactory
{
    /**
     * @param ContainerInterface $container
     * @return SmartyRenderer
     */
    public function __invoke(ContainerInterface $container): SmartyRenderer
    {
        return new SmartyRenderer($container->get(Smarty::class));
    }

}
