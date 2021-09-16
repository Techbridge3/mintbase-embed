<?php

namespace TBMintbase\Helper;

use TBMintbase\Model\Config;

class View
{

    protected static $config;

    /**
     * Method to render template
     *
     * WARNING do not remove  @args It's need to transfer self object, when function calls by hook.
     *
     * @param $templatePath
     * @param $args
     * @return bool
     */
    public static function view($templatePath , $args = null)
    {
        try {
            $error = __('');
            if (!file_exists($templatePath)) {
                throw new \Exception($error);
            }
            $content = require_once ($templatePath);
            if ($content !='') {
                echo $content;
                return true;
            }
            throw new \Exception($error);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return false;
    }

    /**
     *
     * @return Config
     */
    final protected static function getConfig()
    {
        if (!self::$config) {
            self::$config = new Config();
        }
        return self::$config;
    }

    /**
     * @param $templateName
     * @return mixed|string
     */
    final public static function renderParts($templateName, $data = null)
    {

        $templatePath = self::getConfig()->getTemplatesPath()
            . '/frontend/template-parts/'
            . $templateName;
        try {
            $error = __('');
            if (!file_exists($templatePath)) {
                throw new \Exception($error);
            }
            $content = require($templatePath);
            if ($content != '') {
                return $content;
            }
            throw new \Exception($error);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
