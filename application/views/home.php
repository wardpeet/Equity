<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<title>Equity</title>

<link href="<?=base_url('css/styles.css');?>" rel="stylesheet" />
<script src="<?=base_url('js/modernizr.js');?>"></script>
</head>
<body>

<div id="container">
    <a href="#back" class="button" id="back" onclick="return false;">Terug</a>
    <div id="main">
        <img src="<?=base_url('images/image01.jpg');?>" height="375" alt="" rel="1" />
        <div class="caption"><span>Test</span></div>
    </div>

    <div id="children">
        <div><img src="<?=base_url('images/image02.jpg');?>" height="188" alt="Image 02" rel="2" /><div class="caption"><span>Image 02</span></div></div>
        <div><img src="<?=base_url('images/image03.jpg');?>" height="188" alt="Image 03" rel="3" /><div class="caption"><span>Image 03</span></div></div>
        <div><img src="<?=base_url('images/image04.jpg');?>" height="188" alt="Image 04" rel="4" /><div class="caption"><span>Image 04</span></div></div>
    </div>
    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>var siteurl = '<?=site_url();?>';</script>
<script src="<?=base_url('js/equity.js');?>"></script>
</body>
</html>