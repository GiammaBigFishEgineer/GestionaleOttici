<?php
    require_once(__ROOT__ . '/vendor/autoload.php');

    use Phpfastcache\CacheManager;
    use Phpfastcache\Config\ConfigurationOption;
    
    function setup_caching(){
        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => realpath(__ROOT__) . '/.cache',
            'preventCacheSlams' => true,
            'cacheSlamsTimeout' => 20,
        ]));
    }

?>