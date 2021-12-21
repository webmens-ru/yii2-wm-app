<?php
use app\assets\AppAsset;
$assetsUrl = AppAsset::register($this);
$this->registerJsFile($assetsUrl->baseUrl . '/static/js/runtime-main.js');
$this->registerJsFile($assetsUrl->baseUrl . '/static/js/2.chunk.js');
$this->registerJsFile($assetsUrl->baseUrl . '/static/js/main.chunk.js');
?>

<div id="root"></div>
<script>
    window._ACCESS_TOKEN_ = '<?=$accessToken?>';
    window._PARAMS_= <?=$params?>;
    window._APP_URL_='<?=$appUrl?>';
</script>
<script src="//api.bitrix24.com/api/v1/"></script>
