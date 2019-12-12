<div class="row">
    <?php foreach ($tariffs as $item): ?>
        <div class="col-md-6 col-lg-4">
            <?php
                $view = new \SkyNetFront\Core\View('Tariff/ListItem');
                $view->set('tariff', $item);
                echo $view->render();
            ?>
        </div>
    <?php endforeach; ?>
</div>
