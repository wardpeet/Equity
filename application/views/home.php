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
        <img src="<?=$main['image']?>" height="375" alt="" rel="<?=$main['id']?>" />
        <div class="caption"><span><?=$main['title']?></span></div>
    </div>

    <div id="children">
<?php
        foreach($subs AS $cat){
            echo '        <div><img src="'.$cat['image'].'" height="188" alt="'.$cat['title'].'" rel="'.$cat['id'].'" /><div class="caption"><span>'.$cat['title'].'</span></div></div>'."\r\n";
        }
        ?>
    </div>
    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>var siteurl = '<?=site_url();?>';</script>
<script src="<?=base_url('js/equity.js');?>"></script>
</body>
</html>
