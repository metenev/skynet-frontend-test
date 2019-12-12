<div class="tariff-single">
    <header>
        <a class="button-back" href="<?php echo $back_url; ?>">&nbsp;</a>
        <h1><?php echo sprintf( _('Tariff "%s"'), $tariff->field('title') ); ?></h1>
    </header>
    <div class="row">
        <?php foreach ($tariff->getSortedPlans() as $item): ?>
            <div class="col-md-6 col-lg-4">
                <?php
                    $view = new \SkyNetFront\Core\View('Tariff/Plan/ListItem');
                    $view->set('plan', $item);
                    echo $view->render();
                ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
