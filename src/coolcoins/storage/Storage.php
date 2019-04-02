<?php

namespace coolcoins\storage;

use coolcoins\Main;
use pocketmine\plugin\PluginException;
use function strtolower;

class Storage
{
    /**
     * @var \coolcoins\Main
     */
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function load()
    {
        $provider = $this->plugin->getSettings()->getNested('storage.provider');

        switch(strtolower($provider)) {
            case 'mysql':
                return;
            default:
                throw new PluginException('Unknown storage provider specified in config: ' . $provider);
        }
    }
}