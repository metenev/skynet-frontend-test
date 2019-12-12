<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Тарифы SkyNet</title>
    <link rel="stylesheet" href="<?php echo \SkyNetFront\Core\Helper\Url::get('/css/index.css'); ?>">
</head>
    <body>
        <div id="views">
            <div class="view container visible">
                <?php echo $content; ?>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="<?php echo \SkyNetFront\Core\Helper\Url::get('/js/index.js'); ?>"></script>
</html>
