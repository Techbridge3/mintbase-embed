<?php

global $post;

$yesNoFields = [
    [
        'label' => 'Yes',
        'value' => 'yes',
    ],
    [
        'label' => 'No',
        'value' => 'no',
    ],

];

$storeID = get_post_meta($post->ID, 'store_id', true)
    ? get_post_meta($post->ID, 'store_id', true)
    : '';

?>
<?php ob_start();?>
<div class="advanced">
    <?php wp_nonce_field('store_check', 'store_check_value', true);?>
    <div>
        <label for="priority">
            <?php _e('Store id');?>
            <input type="text" name="store_id" value="<?php echo $storeID;?>">
        </label>
    </div>
</div>
<?php return ob_get_clean(); ?>
