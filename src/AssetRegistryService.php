<?php

namespace AssetRegistryPlugin;

class AssetRegistryService
{
    protected $assetRegistry;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'settingsPage'));

        $this->assetRegistry = new AssetRegistry($this->getConfig());
    }

    public function settingsPage()
    {
        add_options_page(
            'Asset Registry Options',
            'Asset Registry',
            'manage_options',
            'asset-registry-settings',
            array(
                $this,
                'settingsPageContent'
            )
        );
    }

    /**
     * @return string
     */
    public function settingsPageContent()
    {
        if (!current_user_can('manage_options')) {

            wp_die(
                __('You do not have sufficient permissions to access this page.')
            );
        }

        $assets = $this->assetRegistry->getAssetList();
        return include __DIR__ . '/../view/settings.phtml';
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/registry.global.php';
    }

    public function registerAssets()
    {
        $this->registerJavascriptAssets();
        $this->registerStyleSheetAssets();
    }

    public function registerJavascriptAssets()
    {
        foreach ($this->assetRegistry->getJsAssetList() as $key => $path) {

            wp_register_script($key, $path);
        }
    }

    public function registerStyleSheetAssets()
    {
        foreach ($this->assetRegistry->getCssAssetList() as $key => $path) {

            wp_register_style($key, $path);
        }
    }
}