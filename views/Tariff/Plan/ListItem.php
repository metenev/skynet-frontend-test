<div class="list-item">
    <header>
        <h2><?php echo $plan->field('title'); ?></h2>
    </header>
    <a class="body-button" href="<?php echo $plan->field('url'); ?>">
        <h3 class="speed"><?php echo $plan->field('price_monthly_label'); ?></h3>
        <p class="details">
            <?php echo $plan->field('price_label'); ?>
            <?php if ($plan->field('one_payment_discount') > 0): ?>
                <br>
                <?php echo $plan->field('one_payment_discount_label'); ?>
            <?php endif; ?>
        </p>
    </a>
</div>