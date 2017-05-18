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
 * The CLI command to run Bookdown.
 *
 * @package bookdown/bookdown
 *
 */
class Command
{
    /**
     *
     * The command-line context.
     *
     * @var Context
     *
     */
    protected $context;

    /**
     *
     * A logger instance.
     *
     * @var LoggerInterface
     *
     */
    protected $logger;

    /**
     *
     * The Bookdown service layer object.
     *
     * @var Context
     *
     */
    protected $service;

    /**
     *
     * Constructor.
     *
     * @param Context $context The command-line context.
     *
     * @param LoggerInterface A logger instance.
     *
     * @param Service $service The Bookdown service layer object.
     *
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        Service $service
    ) {
        $this->context = $context;
        $this->logger = $logger;
        $this->service = $service;
    }

    /**
     *
     * Runs this command.
     *
     * @return int
     *
     */
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

    /**
     *
     * Initializes this command.
     *
     * @return array The names of the root-level config file, and their
     * command-line option overrides.
     *
     */
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
