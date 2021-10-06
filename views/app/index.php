<?php 
use app\assets\AppAsset;
$assetsUrl = AppAsset::register($this);
$this->registerCssFile($assetsUrl->baseUrl . '/static/css/main.chunk.css');
$this->registerJsFile($assetsUrl->baseUrl . '/static/js/2.chunk.js');
$this->registerJsFile($assetsUrl->baseUrl . '/static/js/main.chunk.js');
?>

<div id="root"></div>
<script>
  let params = <?=json_encode($params)?>;
  let accessToken = '<?=$accessToken?>';  
</script>
<script src="//api.bitrix24.com/api/v1/"></script>
<script>      
</script>