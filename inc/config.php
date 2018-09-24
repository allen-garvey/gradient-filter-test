<?php
    //find out if we are running from makefile, or directly through fastcgi
    if(!empty($argv)){
        define('IS_FASTCGI', false);
    }
    else{
        define('IS_FASTCGI', true);
    }

    //get build mode from command-line arguments
    if(!IS_FASTCGI && !empty($argv) && count($argv) > 1 && $argv[1] === 'release'){
        define('BUILD_MODE_RELEASE', true);
    }
    else{
        define('BUILD_MODE_RELEASE', false);
    }


    /*
    * File path constants
    */
    define('ROOT_PATH', dirname(__FILE__, 2).DIRECTORY_SEPARATOR);
    define('INC_PATH', ROOT_PATH.'inc'.DIRECTORY_SEPARATOR);
    
    //php templates paths
    define('TEMPLATES_PATH', ROOT_PATH.'templates'.DIRECTORY_SEPARATOR);
    define('TEMPLATES_INDEX_PATH', TEMPLATES_PATH.'index'.DIRECTORY_SEPARATOR);

    //webgl shaders
    define('TEMPLATES_WEBGL_SHADERS_PATH', TEMPLATES_INDEX_PATH.'webgl_shaders'.DIRECTORY_SEPARATOR);
    define('TEMPLATES_WEBGL_VERTEX_SHADERS_PATH', TEMPLATES_INDEX_PATH.'webgl_shaders'.DIRECTORY_SEPARATOR.'vertex'.DIRECTORY_SEPARATOR);
    define('TEMPLATES_WEBGL_FRAGMENT_SHADERS_PATH', TEMPLATES_INDEX_PATH.'webgl_shaders'.DIRECTORY_SEPARATOR.'fragment'.DIRECTORY_SEPARATOR);
    define('TEMPLATES_WEBGL_SHARED_FRAGMENT_SHADERS_PATH', TEMPLATES_INDEX_PATH.'webgl_shaders'.DIRECTORY_SEPARATOR.'fragment'.DIRECTORY_SEPARATOR.'shared'.DIRECTORY_SEPARATOR);
    define('TEMPLATES_WEBGL_FILTER_FRAGMENT_SHADERS_PATH', TEMPLATES_INDEX_PATH.'webgl_shaders'.DIRECTORY_SEPARATOR.'fragment'.DIRECTORY_SEPARATOR.'filters'.DIRECTORY_SEPARATOR);


    /**
     * URLs
     * */
    define('GITHUB_SOURCE_URL', '');
    define('BASE_URL', '/');
    define('CSS_URL_BASE', BASE_URL.'styles/');
    define('JS_URL_BASE', BASE_URL.'js/');

    define('CSS_APP_URL', CSS_URL_BASE.'style.css');


    if(BUILD_MODE_RELEASE){
        define('JS_BUNDLE_URL', JS_URL_BASE.'bundle.min.js');
    }
    else{
        define('JS_BUNDLE_URL', JS_URL_BASE.'bundle.js');
    }
    
    /*
    * JS application constants
    */
    define('APP_NAME', 'Supra paint');
    define('NUM_GRADIENT_POINTS', 10);
    define('SQRT_2', 1.4142135623730951);
    