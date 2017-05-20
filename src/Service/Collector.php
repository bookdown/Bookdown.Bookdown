<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Service;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\ConfigFactory;
use Bookdown\Bookdown\Content\IndexPage;
use Bookdown\Bookdown\Content\PageFactory;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Fsio;

/**
 *
 * Collects all Pages for later processing.
 *
 * @package bookdown/bookdown
 *
 */
class Collector
{
    /**
     *
     * An array of Page objects.
     *
     * @var array
     *
     */
    protected $pages = [];

    /**
     *
     * A factory for Config objects.
     *
     * @var ConfigFactory
     *
     */
    protected $configFactory;

    /**
     *
     * A factory for Page objects.
     *
     * @var PageFactory
     *
     */
    protected $pageFactory;

    /**
     *
     * A logger implementation.
     *
     * @var LoggerInterface
     *
     */
    protected $logger;

    /**
     *
     * A filesystem I/O object.
     *
     * @var Fsio
     *
     */
    protected $fsio;

    /**
     *
     * The current subdirectory depth or level.
     *
     * @var int
     *
     */
    protected $level = 0;

    /**
     *
     * The previously-processed Page, if any.
     *
     * @var Page
     *
     */
    protected $prev;

    /**
     *
     * Constructor.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param Fsio $fsio A filesystem I/O object.
     *
     * @param ConfigFactory $configFactory A factory for Config objects.
     *
     * @param PageFactory $pageFactory A factory for Page objects.
     *
     */
    public function __construct(
        LoggerInterface $logger,
        Fsio $fsio,
        ConfigFactory $configFactory,
        PageFactory $pageFactory
    ) {
        $this->logger = $logger;
        $this->fsio = $fsio;
        $this->configFactory = $configFactory;
        $this->pageFactory = $pageFactory;
    }

    /**
     *
     * Sets the root-level config override values.
     *
     * @param array $rootConfigOverrides The override values.
     *
     */
    public function setRootConfigOverrides(array $rootConfigOverrides)
    {
        $this->configFactory->setRootConfigOverrides($rootConfigOverrides);
    }

    /**
     *
     * Executes the collection process.
     *
     * @param string $configFile The config file for a directory of pages.
     *
     * @param string $name The name of the current page.
     *
     * @param Page $parent The parent page, if any.
     *
     * @param int $count The current sequential page count.
     *
     * @return RootPage
     *
     */
    public function __invoke($configFile, $name = '', $parent = null, $count = 0)
    {
        $this->padlog("Collecting content from {$configFile}");
        $this->level ++;
        $index = $this->newIndex($configFile, $name, $parent, $count);
        $this->addContent($index);
        $this->level --;
        return $index;
    }

    /**
     *
     * Adds and returns a new IndexPage.
     *
     * @param string $configFile The config file for a directory of pages.
     *
     * @param string $name The name of the current page.
     *
     * @param Page $parent The parent page, if any.
     *
     * @param int $count The current sequential page count.
     *
     * @return RootPage\IndexPage
     *
     */
    protected function newIndex($configFile, $name, $parent, $count)
    {
        if (! $parent) {
            return $this->addRootPage($configFile);
        }

        return $this->addIndexPage($configFile, $name, $parent, $count);
    }

    /**
     *
     * Adds child pages to an IndexPage.
     *
     * @param IndexPage $index The IndexPage to add to.
     *
     */
    protected function addContent(IndexPage $index)
    {
        $count = 1;
        foreach ($index->getConfig()->getContent() as $name => $file) {
            $child = $this->newChild($file, $name, $index, $count);
            $index->addChild($child);
            $count ++;
        }
    }

    /**
     *
     * Creates and returns a new Page object.
     *
     * @param string $file The file for the page; if a bookdown.json file,
     * recurses into its contents.
     *
     * @param string $name The name of the current page.
     *
     * @param IndexPage $index The index page over this one.
     *
     * @param int $count The current sequential page count.
     *
     * @return Page
     *
     */
    protected function newChild($file, $name, IndexPage $index, $count)
    {
        $bookdown_json = 'bookdown.json';
        $len = -1 * strlen($bookdown_json);

        if (substr($file, $len) == $bookdown_json) {
            return $this->__invoke($file, $name, $index, $count);
        }

        return $this->addPage($file, $name, $index, $count);
    }

    /**
     *
     * Appends a Page to $pages.
     *
     * @param string $origin The Markdown file for the page.
     *
     * @param string $name The name of the current page.
     *
     * @param IndexPage $parent The parent page over this one.
     *
     * @param int $count The current sequential page count.
     *
     * @return Page
     *
     */
    protected function addPage($origin, $name, IndexPage $parent, $count)
    {
        $page = $this->pageFactory->newPage($origin, $name, $parent, $count);
        $this->padlog("Added page {$page->getOrigin()}");
        return $this->append($page);
    }

    /**
     *
     * Adds the root page to $pages.
     *
     * @param string $configFile The root-level config file
     *
     * @return RootPage
     *
     */
    protected function addRootPage($configFile)
    {
        $data = $this->fsio->get($configFile);
        $config = $this->configFactory->newRootConfig($configFile, $data);
        $page = $this->pageFactory->newRootPage($config);
        $this->padlog("Added root page from {$configFile}");
        return $this->append($page);
    }

    /**
     *
     * Adds and returns a new IndexPage.
     *
     * @param string $configFile The config file for a directory of pages.
     *
     * @param string $name The name of the current page.
     *
     * @param Page $parent The parent page, if any.
     *
     * @param int $count The current sequential page count.
     *
     * @return RootPage\IndexPage
     *
     */
    protected function addIndexPage($configFile, $name, $parent, $count)
    {
        $data = $this->fsio->get($configFile);
        $config = $this->configFactory->newIndexConfig($configFile, $data);
        $page = $this->pageFactory->newIndexPage($config, $name, $parent, $count);
        $this->padlog("Added index page from {$configFile}");
        return $this->append($page);
    }

    /**
     *
     * Appends a page to $pages.
     *
     * @param Page $page The page to append.
     *
     * @return Page
     *
     */
    protected function append(Page $page)
    {
        if ($this->prev) {
            $this->prev->setNext($page);
            $page->setPrev($this->prev);
        }
        $this->pages[] = $page;
        $this->prev = $page;
        return $page;
    }

    /**
     *
     * Logs a message, with padding.
     *
     * @param string $str The message to log.
     *
     */
    protected function padlog($str)
    {
        $pad = str_pad('', $this->level * 2);
        $this->logger->info("{$pad}{$str}");
    }
}
