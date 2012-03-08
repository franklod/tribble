<?=doctype('html5')?>
<head>
  <!-- <link rel="stylesheet" href="http://f.fontdeck.com/s/css/jrnfacj2+/6qJZMV0RqKKjVd0Pc/10.134.132.97/15877.css" type="text/css" /> -->
  <!-- <link type="text/css" rel="stylesheet" href="<?=cdn_url('/assets/css/tribble.min.css')?>" />   -->
  <link type="text/css" rel="stylesheet" href="<?=cdn_url('/assets/css/addictive.css')?>" />  
  <link type="text/css" rel="stylesheet" href="<?=cdn_url('/assets/css/tribble.css')?>" />  
  <link type="text/css" rel="stylesheet" href="<?=cdn_url('/assets/css/tagsinput.css')?>" />  
  <title><?=$title?></title>
  <meta charset="utf-8">
  <meta name="description" content="<?=$meta_description?>" />
  <meta name="keywords" content="<?=$meta_keywords?>" />
  <?if(isset($css)):?>
    <?=$css?>
  <?endif;?>
</head>
<body>
<div id="overlay" class="black_overlay">
  <div class="wrapper">
    <div id="light" class="overlay_content">
      <span class="loader">&nbsp;</span>
      <h4>Please wait while your image is processed.</h4>
    </div>
  </div>
</div>
