<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SkyNet :: Страница не найдена</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font: 26px/50px 'Arial', sans-serif;
        }
        .error-display {
            display: flex;
            width: 100%;
            height: 100%;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="error-display">
        <h1><?php echo _('Page not found'); ?></h1>
        <p>
            <a href="<?php echo \SkyNetFront\Core\Helper\Url::get('/'); ?>"><?php echo _('Go to Homepage'); ?></a>
        </p>
    </div>
</body>
</html>