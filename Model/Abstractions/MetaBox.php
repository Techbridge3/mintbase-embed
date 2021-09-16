<?php

namespace TBMintbase\Model\Abstractions;

/**
 * Class MetaBox
 * @package TBMintbase\Model\Constructor
 */
abstract class MetaBox
{
    /**
     * MetaBox constructor.
     */
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'addMetaBoxes']);
        add_action('save_post', [$this, 'saveData']);
    }

    /**
     * Method to add metaboxes
     *
     * @param $postType
     * @return mixed
     */
    abstract public function addMetaBoxes( $postType );

    /**
     * Method to save meta data
     *
     * @param $postId
     * @return mixed
     */
    abstract public function saveData( $postId );

    /**
     * Method to render meta content
     *
     * @param $post
     * @return mixed
     */
    abstract public function renderMetaBoxContent( $post );
}
