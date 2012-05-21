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
    <div id="main"><img src="<?=base_url('images/image01.jpg');?>" height="375" alt="" rel="1" /></div>

    <div id="children"><img src="<?=base_url('images/image02.jpg');?>" height="188" alt="" rel="2" /><img src="<?=base_url('images/image03.jpg');?>" height="188" alt="" rel="3" /><img src="<?=base_url('images/image04.jpg');?>" height="188" alt="" rel="4" /></div>
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

    var width = 0;
    $('img', $children).each(function() {
        width += $(this).width() + 10;
    });
    width = $children.width() / 2 - width / 2;
    $('img', $children).each(function() {
        $(this).css('left', width + 'px');
        width += $(this).width() + 10;
    });

    function updateMainImage($this) {
        $mImg = $('img', $main);
        $img = $this.clone()
            .css({'z-index': 5,
                left: 0})
            .height(375)
            .hide();
        $main.append($img);

        $mImg.fadeOut('slow');
        $img.fadeIn('slow', function() {
            $mImg.remove();
            ++count;
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
        var length = $images.length;
        var count = 0;
        for(var i=0; i<data.length; i++) {
            var $img = $(document.createElement('img'))
                .attr('src', data[i].image)
                .attr('rel', data[i].id)
                .attr('alt', data[i].title)
                .css({left: $images.eq(i).css('left')})
                .height(188)
                .hide();
            $img.click(function() {
                updateMainImage($(this));
            });
            
            $children.append($img);
            $images.eq(i).fadeOut('slow', function() {
                ++count;
                $(this).remove();
            });
            $img.fadeIn('slow');
        }

        width = 0;
        $images = $('img', $children);
        for(var j=data.length; j < length; j++) {
            width += $images.eq(j).width() + 12;
            $images.eq(j).remove();
        }
        
        if (width) {
            width /= 2;

            $images = $('img', $children);
            for(var j=data.length - count; j <= length; j++) {
                var left = $images.eq(j).css('left');
                $images.eq(j).css('left', (parseInt(left.substring(0, left.length - 2)) + width) + 'px');
            }
        }
    }

    function setPosition($images, length) {
        if(count == length) {
            $images.each(function() {
                var left = $(this).css('left');
                $(this).css('left', (parseInt(left.substring(0, left.length - 2))+ width) + 'px');
            });
        } else {
            setTimeout(function() { setPosition($images, length) }, 200);
        }
    }
});</script>
</body>
</html>