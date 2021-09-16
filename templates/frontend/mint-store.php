<?php

use TBMintbase\Model\MintBaseModel;

$storeID = get_post_meta(get_the_ID(), 'store_id', true);
$mModel = new MintBaseModel();
$storeData = [];
$singleThingData = [];

if (!isset($_GET['thing'])) {
    $storeData = $mModel->getStoreById($storeID);
} else {
    $singleThingData = $mModel->getThingById($_GET['thing']);
}

?>
<?php get_header(); ?>
<div id="content" class="site-content">
    <div class="ast-container">
        <div id="primary" class="content-area primary">
            <main id="main" class="site-main">
                <section id="store">
                    <?php if (!empty($storeData)): ?>
                        <h1>STORE</h1>
                        <div class="store">
                            <?php echo " ID $storeData->id "; ?>
                            <?php echo " Name $storeData->name "; ?>
                            <?php echo " Owner $storeData->owner "; ?>
                        </div>
                        <div class="things">
                            <h1>THINGS</h1>
                            <?php if (!empty($storeData->things)): ?>
                                <?php foreach ($storeData->things as $thing): ?>
                                    <a href="<?php echo home_url($_SERVER['REQUEST_URI'] . "?thing={$thing->id}"); ?>">
                                        <img width="20%" height="20%" src="<?php echo $thing->nft['media']; ?>"
                                             title="<?php echo $thing->nft['title']; ?>"
                                             alt="<?php echo $thing->nft['title']; ?>"
                                        />
                                    </a>
                                    <div class="description">
                                        <?php echo $thing->nft['description']; ?> <br>
                                        <?php echo "copies {$thing->nft['copies']}"; ?> <br>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <h1><?php _e('Not things'); ?></h1>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </section>
                <section id="thing">
                    <?php if (!empty($singleThingData)): ?>
                        <img src="<?php echo $singleThingData->nft['media']; ?>"
                             title="<?php echo $singleThingData->nft['title']; ?>"
                             alt="<?php echo $singleThingData->nft['title']; ?>"
                        />
                        <?php echo json_encode($singleThingData); ?>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>
</div>

<?php get_footer(); ?>
