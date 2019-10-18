<div class='inside'>

    <h3>Resource Identifier</h3>
    <p>
        <input disabled type="text" name="<?php echo Blink\Constants::ARTICLE_RESOURCE_ID_INPUT_NAME; ?>"
               value="<?php echo $current_resource_id; ?>"/>
    </p>


    <h3>Price</h3>
    <p>
        <input id="articlePriceInput"
               type="number" min="0" step="0.01"
               name="<?php echo Blink\Constants::ARTICLE_PRICE_INPUT_NAME; ?>"
               value="<?php echo $current_price; ?>"/>
    </p>

    <div style="margin-top: 25px; padding-left: 1px">
        <input id="defaultPriceCheckBox"
               type="checkbox" name="<?php echo Blink\Constants::ARTICLE_USE_DEFAULT_PRICE ?>"
               value="<?php echo Blink\Constants::ARTICLE_USE_DEFAULT_PRICE_VALUE?>"
            <?php if($hasDefaultPrice == "true"){
                echo "checked";
            } ?>
        >Use default price of <?php echo $defaultArticlePrice; ?>&#36;<br>
    </div>
</div>
<script>
    (function () {

        const priceInput = document.getElementById('articlePriceInput');
        const defaultPriceCheckBox = document.getElementById('defaultPriceCheckBox');
        priceInput.addEventListener('change', uncheckDefaultPrice );

        function uncheckDefaultPrice() {
            defaultPriceCheckBox.checked = false;
        }

    })();
</script>
