<?php

declare(strict_types = 1);

namespace SolcreMezzio\Smarty;

use Mezzio\Template\ArrayParametersTrait;
use Mezzio\Template\DefaultParamsTrait;
use Mezzio\Template\TemplatePath;
use Mezzio\Template\TemplateRendererInterface;
use Smarty;
use SmartyException;
use function preg_match;
use function preg_replace;
use function sprintf;

/**
 * Template implementation bridging twig/twig
 */
class SmartyRenderer implements TemplateRendererInterface
{
    use ArrayParametersTrait;
    use DefaultParamsTrait;

    private Smarty $template;

    public function __construct(Smarty $template)
    {
        $this->template = $template;
    }

    public function render(string $name, $params = []): string
    {
        $model = null;
        $this->template->assign($params);
        try {
            return $this->template->fetch($name) ?? '';
        } catch (SmartyException $e) {
            throw $e;
        }
    }

    /**
     * Add a path for template
     *
     * @param string $path
     * @param string|null $namespace
     *
     */
    public function addPath(string $path, string $namespace = null): void
    {
        $this->template->addTemplateDir($path);
    }

    /**
     * Get the template directories
     *
     * @return TemplatePath[]
     */
    public function getPaths(): array
    {
        $templateDir = $this->template->getTemplateDir();
        if (\is_array($templateDir)) {
            return $templateDir;
        }

        $paths = [];
        $paths[0] = $this->template->getTemplateDir();
        return $paths;
    }

    /**
     * Normalize namespaced template.
     *
     * Normalizes templates in the format "namespace::template" to
     * "@namespace/template".
     *
     * @param string $template
     *
     * @return string
     */
    public function normalizeTemplate(string $template): string
    {
        $template = preg_replace('#^([^:]+)::(.*)$#', '@$1/$2', $template);
        if (! preg_match('#\.[a-z]+$#i', $template)) {
            return sprintf('%s.%s', $template, 'tpl');
        }

        return $template;
    }
}
