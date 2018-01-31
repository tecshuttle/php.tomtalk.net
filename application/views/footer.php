<?php
//设置默认值
$js = (isset($js) ? $js : array());
$css_js_version = $this->config->config['css_js_version'];
?>



<?php foreach ($js as $jsFile): ?>
    <script src="<?= $jsFile ?>?version=<?= $css_js_version ?>" type="text/javascript"></script>
<?php endforeach; ?>

</body>
</html>