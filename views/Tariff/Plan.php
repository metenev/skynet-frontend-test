<div class="plan-single">
    <header>
        <a class="button-back" href="<?php echo $back_url; ?>">&nbsp;</a>
        <h1><?php echo _('Tariff select'); ?></h1>
    </header>
    <div class="list-item">
        <header>
            <h2><?php echo sprintf( _('Tariff "%s"'), $tariff->field('title') ); ?></h2>
        </header>
        <article>
            <h3 class="info">
                <?php echo sprintf( _('Payment period &ndash; %s'), sprintf( ngettext('%d month', '%d months', $plan->field('pay_period')), $plan->field('pay_period') ) ); ?>
                <br>
                <?php echo $plan->field('price_monthly_label'); ?>
            </h3>
            <p class="details">
                <?php echo $plan->field('price_label'); ?>
                <br>
                <?php echo $plan->field('withdraw_amount_label'); ?>
            </p>
            <p class="details-secondary">
                <?php echo $start_label; ?>
                <br>
                <?php echo $end_label; ?>
            </p>
        </article>
        <footer>
            <button class="button-buy"><?php echo _('Select'); ?></button>
        </footer>
    </div>
</div>
