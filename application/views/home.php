<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<title>Equity</title>

<link href="<?=base_url('css/styles.css');?>" rel="stylesheet" />
</head>
<body>

<div id="container">
    <div id="main"><img src="<?=base_url('images/image01.jpg');?>" width="500" height="375" alt="" rel="1" /></div>

    <div id="children"><img src="<?=base_url('images/image02.jpg');?>" width="250" height="188" alt="" rel="2" /><img src="<?=base_url('images/image03.jpg');?>" width="250" height="188" alt="" rel="3" /><img src="<?=base_url('images/image04.jpg');?>" width="250" height="188" alt="" rel="4" /></div>
    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>$(function() {
    var $img,
        $main = $('#main'),
        $children = $('#children');
    $('img', '#children').click(function() {
        updateMainImage($(this));
    });

    function updateMainImage($this) {
        $mImg = $('img', $main);
        $img = $this.clone()
            .css({'z-index': 5})
            .width(500)
            .height(375)
            .hide();
        $main.append($img);

        $mImg.fadeOut('slow');
        $img.fadeIn('slow', function() {
            $mImg.remove();
        });

        $.get('<?=site_url('home/next');?>', 'category=' + $this.attr('rel'), function(data) {
            if (data.next) {
                createNewImages(data.data);
            } else {
                createInfoPage(data.data);
            }
        }, 'json');
    }

    function createInfoPage(data) {
        $('img', $children).remove();
        $children.addClass('text');
        
        $children.html(data.content);   
    }
    
    function createNewImages(data) {
        var $images = $('img', $children);
        for(var i=0; i<data.length; i++) {
            var $img = $(document.createElement('img'))
                .attr('src', data[i].image)
                .attr('rel', data[i].id)
                .attr('alt', data[i].title)
                .width(250)
                .height(188);
            $img.click(function() {
                updateMainImage($(this));
            });

            $images.eq(i).remove();
            $children.append($img);
        }

        $images = $('img', $children);
        for(var j=0; j<$images.length - i; j++) {
            $images.eq(j).remove();
        }
    }
});</script>
</body>
</html>