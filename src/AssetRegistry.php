<?php
/**
 * @author Curtis Kelsey <kelseyc@truman.edu>
 * @copyright Copyright (c) Truman State University
 */
namespace AssetRegistryPlugin;

/**
 * Class AssetRegistry
 * @package AssetRegistryPlugin
 */
class AssetRegistry
{
    /** @var  array */
    protected $javascriptAssets;

    /** @var  array */
    protected $styleSheetAssets;

    /**
     * AssetRegistry constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $config = $config['asset-registry'];

        $this->javascriptAssets = $config['javascript'];
        $this->normalizePathArray($this->javascriptAssets);

        $this->styleSheetAssets = $config['stylesheet'];
        $this->normalizePathArray($this->styleSheetAssets);
    }

    /**
     * Returns all of the assets registered
     */
    public function getAssetList()
    {
        return [
            'javascript' => $this->javascriptAssets,
            'stylesheet' => $this->styleSheetAssets
        ];
    }

    /**
     * Returns all of the CSS assets registered
     * @return array
     */
    public function getCssAssetList()
    {
        return $this->styleSheetAssets;
    }

    /**
     * Returns all of the JS assets registered
     * @return array
     */
    public function getJsAssetList()
    {
        return $this->javascriptAssets;
    }

    public function getRegisteredJsAssets()
    {
        global $wp_scripts;

        return $wp_scripts->registered;
    }

    public function getRegisteredCssAssets()
    {
        global $wp_styles;

        return $wp_styles->registered;
    }

    /**
     * @param string $key
     * @param string $fileUrl
     * @return $this
     */
    public function addJsAsset($key, $fileUrl)
    {
        $this->javascriptAssets[$key] = $this->normalizePath($fileUrl);
        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function removeJsAsset($key)
    {
        unset($this->javascriptAssets[$key]);
        return $this;
    }

    /**
     * @param string $key
     * @param string $filePath
     * @return $this
     */
    public function addCssAsset($key, $filePath)
    {
        $this->styleSheetAssets[$key] = $this->normalizePath($filePath);
        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function removeCssAsset($key)
    {
        unset($this->styleSheetAssets[$key]);
        return $this;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function normalizePath($path)
    {
        if (substr($path, 0, 4) !== 'http' || substr($path, 0, 2) !== '//') {

            return plugin_dir_url(__FILE__) . $path;
        }

        return $path;
    }

    /**
     * @param array $array
     */
    protected function normalizePathArray(array &$array)
    {
        foreach ($array as $key => $path)
        {
            $array[$key] = $this->normalizePath($path);
        }
    }
}
