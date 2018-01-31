<!DOCTYPE html>
<html <?= (isset($appName) ? "ng-app='$appName'" : '') ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title ng-bind="title"><?= $title ?></title>

    <?php
    //设置默认值
    $css_js_version = $this->config->config['css_js_version'];;
    $css = (isset($css) ? $css : array());
    ?>

    <?php foreach ($css as $cssFile): ?>
        <link rel="stylesheet" type="text/css" href="<?= $cssFile ?>?version=<?= $css_js_version ?>"/>
    <?php endforeach; ?>

</head>

<body>