<?php
/**
 * @author Curtis Kelsey <kelseyc@truman.edu>
 * @copyright Copyright (c) Truman State University
 */
namespace AssetRegistryPlugin;

class AssetRegistryService
{
    protected $assetRegistry;

    public function __construct()
    {
        if (!class_exists('WP_List_Table')) {

            require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
        }

        add_action('admin_menu', array($this, 'settingsPage'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));

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

    public function enqueue_assets()
    {
        foreach ($this->assetRegistry->getJsAssetList() as $handle => $source) {

            wp_register_script($handle, $source);
        }

        foreach ($this->assetRegistry->getCssAssetList() as $handle => $source) {

            wp_register_style($handle, $source);
        }
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

        $myListTable = new AssetTable();

        $myListTable->addData(
            $this->assetRegistry->getRegisteredJsAssets(),
            'Javascript'
        );
        $myListTable->addData(
            $this->assetRegistry->getRegisteredCssAssets(),
            'Stylesheet'
        );

        echo '<div class="wrap"><h2>Registered Assets</h2>';

        $myListTable->prepare_items();
        $myListTable->display();
        echo '</div>';
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