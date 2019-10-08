<div class='inside'>

    <h3>Resource Identifier</h3>
    <p>
        <input disabled type="text" name="<?php echo Blink\Constants::ARTICLE_RESOURCE_ID_INPUT_NAME; ?>"
               value="<?php echo $current_resource_id; ?>"/>
    </p>

    <h3>Price</h3>
    <p>
        <input type="number" min="0" step="0.01"
               name="<?php echo Blink\Constants::ARTICLE_PRICE_INPUT_NAME; ?>"
               value="<?php echo $current_price; ?>"/>
    </p>

</div>
