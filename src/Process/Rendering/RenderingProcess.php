<?php
namespace Bookdown\Bookdown\Process\Rendering;

use Bookdown\Bookdown\Service\AssetManager;
use Bookdown\Bookdown\Service\AssetManager\AssetManagerAwareInterface;
use Psr\Log\LoggerInterface;
use Aura\View\View;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;

class RenderingProcess implements ProcessInterface, AssetManagerAwareInterface
{
    protected $logger;
    protected $fsio;
    protected $view;
    protected $assetManager;

    public function __construct(
        LoggerInterface $logger,
        Fsio $fsio,
        View $view
    ) {
        $this->logger = $logger;
        $this->fsio = $fsio;
        $this->view = $view;
    }

    public function __invoke(Page $page)
    {
        $file = $page->getTarget();
        $this->logger->info("    Rendering {$file}");
        $this->view->page = $page;
        $this->view->html = $this->fsio->get($page->getTarget());
        $this->view->assets = $this->assetManager;
        $result = $this->view->__invoke();
        $this->fsio->put($file, $result);
    }

    public function setAssetManager(AssetManager $assetManager)
    {
        $this->assetManager = $assetManager;
    }
}
