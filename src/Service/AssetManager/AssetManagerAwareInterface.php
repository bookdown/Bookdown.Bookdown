<?php
namespace Bookdown\Bookdown\Service\AssetManager;

use Bookdown\Bookdown\Service\AssetManager;

interface AssetManagerAwareInterface {
    public function setAssetManager(AssetManager $assetManager);
}