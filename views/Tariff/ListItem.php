<div class="list-item">
    <header>
        <h2><?php echo sprintf( _('Tariff "%s"'), $tariff->field('title') ); ?></h2>
    </header>
    <a class="body-button" href="<?php echo $tariff->field('url'); ?>">
        <h3 class="badge-speed" style="background-color: <?php echo $tariff->field('color'); ?>;"><?php echo $tariff->field('speed_label'); ?></h3>
        <p class="price"><?php echo $tariff->field('price_range_label'); ?></p>
        <?php if ($tariff->field('free_options')): ?>
            <p class="options">
                <?php echo implode('<br>', $tariff->field('free_options')); ?>
            </p>
        <?php endif; ?>
    </a>
    <footer>
        <a href="<?php echo $tariff->field('help_link'); ?>" target="_blank"><?php echo $tariff->field('help_label'); ?></a>
    </footer>
</div>