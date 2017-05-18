<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown;

use Psr\Log\LoggerInterface;
use Aura\Cli\Context;
use Bookdown\Bookdown\Service\Service;
use Exception as AnyException;

/**
 *
 *
 *
 * @package bookdown/bookdown
 *
 */
class Command
{
    protected $context;
    protected $logger;
    protected $service;

    public function __construct(
        Context $context,
        LoggerInterface $logger,
        Service $service
    ) {
        $this->context = $context;
        $this->logger = $logger;
        $this->service = $service;
    }

    public function __invoke()
    {
        try {
            list($rootConfigFile, $rootConfigOverrides) = $this->init();
            $this->service->__invoke($rootConfigFile, $rootConfigOverrides);
            return 0;
        } catch (AnyException $e) {
            $this->logger->error($e->getMessage());
            $code = $e->getCode() ? $e->getCode() : 1;
            return $code;
        }
    }

    protected function init()
    {
        $getopt = $this->context->getopt(array(
            'template:',
            'target:',
            'root-href:'
        ));

        if ($getopt->hasErrors()) {
            $errors = $getopt->getErrors();
            $error = array_shift($errors);
            throw $error;
        }

        $rootConfigFile = $getopt->get(1);
        if (! $rootConfigFile) {
            throw new Exception(
                "Please enter the path to a bookdown.json file as the first argument."
            );
        }

        $rootConfigOverrides = $getopt->get();
        return array($rootConfigFile, $rootConfigOverrides);
    }
}
