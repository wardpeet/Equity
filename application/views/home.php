<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Equity</title>

<link href="<?=base_url('css/styles.css');?>" rel="stylesheet" />
</head>
<body>

<div id="container">
    <div id="main"><img src="<?=base_url('images/image01.jpg');?>" width="500" height="375" alt="" rel="1" /></div>

    <div id="children"><img src="<?=base_url('images/image02.jpg');?>" width="500" height="375" alt="" rel="2" /> <img src="<?=base_url('images/image03.jpg');?>" width="500" height="375" alt="" rel="3" /> <img src="<?=base_url('images/image04.jpg');?>" width="500" height="375" alt="" rel="4" /></div>
    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>$(function() {
    var $img,
        $main = $('#main'),
        $children = $('#children');
    $('img', '#children').click(function() {
        $mImg = $('img', $main);
        $img = $(this).clone()
            .css({'z-index': 5})
            .hide();
        $main.append($img);

        $mImg.fadeOut('slow');
        $img.fadeIn('slow', function() {
            $mImg.remove();
        });

        $.get('<?=site_url('home/next');?>', 'category=' + $(this).attr('rel'), function(data) {
            if (data.next) {
                createNewImages(data.data);
            } else {
                createInfoPage(data.data);
            }
        }, 'json');
    });

    function createInfoPage(data) {
        
    }
    
    function createNewImages(data) {
        alert(data);
    }
});</script>
</body>
</html>