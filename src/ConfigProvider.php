<?php

declare(strict_types = 1);

namespace SolcreMezzio\Smarty;

use Mezzio\Template\TemplateRendererInterface;
use Smarty;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases'   => [
                TemplateRendererInterface::class => SmartyRenderer::class,
            ],
            'factories' => [
                Smarty::class         => SmartyEnvironmentFactory::class,
                SmartyRenderer::class => SmartyRendererFactory::class,
            ],
        ];
    }

    public function getTemplates(): array
    {
        return [
            'extension' => '.tpl',
            'paths'     => [],
        ];
    }
}
