<?php

declare(strict_types = 1);

namespace SolcreMezzio\Smarty;

use ArrayObject;
use Psr\Container\ContainerInterface;
use Smarty;
use function get_class;
use function gettype;
use function is_array;
use function is_object;
use function sprintf;

class SmartyEnvironmentFactory
{
    /**
     * @param ContainerInterface $container
     * @return Smarty
     */
    public function __invoke(ContainerInterface $container): Smarty
    {
        $config = $container->get('config');

        if (! is_array($config) && ! $config instanceof ArrayObject) {
            throw new Exception\InvalidConfigException(sprintf(
                '"config" service must be an array or ArrayObject for the %s to be able to consume it; received %s',
                __CLASS__,
                (is_object($config) ? get_class($config) : gettype($config))
            ));
        }
        $smarty = new Smarty();

        $compileDir = $config['smarty']['compile_dir'];
        if (empty($compileDir)) {
            throw new Exception\InvalidConfigException('Compile dir can not be empty');
        }

        $smarty->setCompileDir($compileDir);
        $smarty->setCacheDir($config['smarty']['cache_dir'] ?? '/tmp');

        $allPaths = isset($config['templates']['paths']) && is_array($config['templates']['paths']) ? $config['templates']['paths'] : [];
        if (! isset($allPaths['templates'])) {
            throw new Exception\InvalidConfigException('Templates directory can not be empty');
        }
        $smarty->setTemplateDir($allPaths['templates'] ?? []);

        $smartyOptions = $config['smarty']['smarty_options'] ?? [];
        // set Smarty engine options
        foreach ($smartyOptions as $key => $value) {
            $setter = 'set' . str_replace(
                    ' ',
                    '',
                    ucwords(str_replace('_', ' ', $key))
                );
            if (method_exists($smarty, $setter)) {
                $smarty->$setter($value);
            } elseif (property_exists($smarty, $key)) {
                $smarty->$key = $value;
            }
        }

        return $smarty;
    }
}
