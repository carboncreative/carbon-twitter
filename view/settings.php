<?php
/**
 * View for settings page
 *
 * @author Tom Moitié
 **/
?>
<div class="wrap carbon_video">
    <h2>Twitter settings</h2>
    <?php if($out['success']) : ?>
        <div class="updated below-h2">
            <p>Settings updated.</p>
        </div>
    <?php endif; ?>
    <?php if($out['nonce_incorrect']) : ?>
        <div class="error below-h2">
            <p>A verification error occured.</p>
        </div>
    <?php endif; ?>
    <form method="post" action="">
        <?=$out['nonce']; ?>
        <p class="label">
            <label for="carbon_twitter_key">Twitter consumer key</label>
        </p>
        <div class="input-wrap">
            <input type="text" name="carbon_twitter_key" id="carbon_twitter_key" value="<?=$out['carbon_twitter_key']; ?>">
        </div>
        <p class="label">
            <label for="carbon_twitter_secret">Twitter consumer secret</label>
        </p>
        <div class="input-wrap">
            <input type="text" name="carbon_twitter_secret" id="carbon_twitter_secret" value="<?=$out['carbon_twitter_secret']; ?>">
        </div>
        <p class="label">
            <label for="carbon_twitter_token">Twitter account token</label>
        </p>
        <div class="input-wrap">
            <input type="text" name="carbon_twitter_token" id="carbon_twitter_token" value="<?=$out['carbon_twitter_token']; ?>">
        </div>
        <p class="label">
            <label for="carbon_twitter_token_secret">Twitter account token secret</label>
        </p>
        <div class="input-wrap">
            <input type="text" name="carbon_twitter_token_secret" id="carbon_twitter_token_secret" value="<?=$out['carbon_twitter_token_secret']; ?>">
        </div>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
        </p>
    </form>
</div>
