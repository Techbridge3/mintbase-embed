<?php

namespace TBMintbase\Model\Constructor;

use TBMintbase\Model\Config;
use TBMintbase\Helper\Data;
use TBMintbase\Model\PageTypeTemplateMapper;

/**
 * Init all main functionality
 *
 * Class Constructor
 * @package TBMintbase\Model\Constructor
 */
class Constructor
{
    /**
     * Self Constructor object.
     * @var $_instance
     */
    private static $_instance;

    /**
     * @var Config
     */
    private $config;

    /**
     * protect singleton  clone
     */
    private function __clone()
    {

    }

    /**
     * Method to use native functions as methods
     *
     * @param $name
     * @param $arguments
     * @return bool|mixed
     */
    public function __call($name, $arguments)
    {
        if (function_exists($name)) {
            return call_user_func_array($name, $arguments);
        }
        return false;
    }

    /**
     * protect singleton __wakeup
     */
    private function __wakeup()
    {

    }

    private function __construct()
    {
        $this->config = new Config();
        $this->setUpActions();
        $this->setUp();
    }

    protected function setUp()
    {
    }


    public function addFrontendStuffs()
    {
        $this->initFrontendControllers();
    }

    /**
     * Method to register plugin scripts
     */
    public function addScripts()
    {

    }

    protected function initFrontendControllers()
    {
    }

    /**
     * Method to setup WP actions.
     */
    private function setUpActions()
    {
        add_action('plugins_loaded', [&$this, 'setPageTemplates']);
        add_action('admin_init', [&$this, 'addAdminStuffs']);
        add_action('init', [&$this, 'addFrontendStuffs']);
        add_action('init', [&$this, 'addScripts']);
        add_action('admin_init', [&$this, 'addMetaBoxes']);
    }

    /**
     *  Method to add meta boxes
     */
    public function addMetaBoxes()
    {
        new MintbaseMetaBox($this->config);
    }

    public function setPageTemplates()
    {
        $templates = Data::modifyTemplatesDataForCreation($this->config->getConfig('page-templates-types'));
        PageTypeTemplateMapper::getInstance($this->config, $templates);
    }

    /**
     * Get self object
     *
     * @return Constructor object
     */
    public static function getInstance()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
