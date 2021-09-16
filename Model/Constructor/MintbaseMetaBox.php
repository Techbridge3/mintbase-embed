<?php

namespace TBMintbase\Model\Constructor;

use TBMintbase\Helper\Data;
use TBMintbase\Model\Abstractions\MetaBox;
use TBMintbase\Helper\View;

/**
 * Class RedeemMetaBox
 * @package Mintbase\Model\Constructor
 */
class MintbaseMetaBox extends MetaBox
{
    const FIELDS_TO_SAVE = [
        'store_id',
    ];

    const FIELDS_TO_CLEAR = [
        'store_id',
    ];

    /**
     * @var \TBMintbase\Model\Config
     */
    private $config;

    /**
     * BlocksOrderMetaBox constructor.
     * @param $config
     */
    public function __construct($config)
    {
        parent::__construct();
        $this->config = $config;
    }

    /**
     * Method to add metabox to mintbase page template.
     *
     * @param $postType
     * @return void
     */
    public function addMetaBoxes($postType)
    {
        global $post;
        $postTypes = ['page'];

        if (in_array($postType, $postTypes) && get_post_meta($post->ID, '_wp_page_template', true) == 'frontend/mint-store.php') {
            add_meta_box(
                'mint_store_metabox',
                __('Mint store'),
                [$this, 'renderMetaBoxContent']
                ,$postType
                ,'advanced'
                ,'high'
            );
        }
    }

    /**
     * Method to check correct current save
     *
     * @param $postId
     * @param $postData
     * @return bool
     */
    private function checkCorrectData($postId, $postData)
    {
        if (!isset($postData['store_check_value'])) {
            return false;
        }
        $nonce = $postData['store_check_value'];
        if (!wp_verify_nonce($nonce, 'store_check')) {
            return false;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return false;
        }
        if (!current_user_can('edit_post', $postId)) {
            return false;
        }
        return true;
    }
    /**
     * Method to save announcement data
     *
     * @param $postId
     * @return bool
     */
    public function saveData($postId)
    {
        if ($_POST) {
            if ($this->checkCorrectData($postId, $_POST)) {
                if(is_admin()) {
                    foreach (self::FIELDS_TO_SAVE as $key) {
                        if (isset($_POST[$key]) && $_POST[$key]) {
                            if (in_array($key, self::FIELDS_TO_CLEAR)) {
                                if (is_array($_POST[$key])) {
                                    $value =  Data::clearArray($_POST[$key]);
                                } else {
                                    $value = Data::clearString($_POST[$key]);
                                }
                            } else {
                                $value = $_POST[$key];
                            }
                            update_post_meta($postId, $key, $value);
                        }
                    }
                }
            }
        }
        return $postId;
    }
    /**
     * Method to get rendered metabox
     *
     * @param $post
     * @return void
     */
    public function renderMetaBoxContent($post)
    {
        $path = $this->config->getTemplatesPath(). '/mint-store-metabox.php';
        View::view($path);
    }

}
