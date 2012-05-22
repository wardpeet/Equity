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
<script>$(function() {
    var $img,
        $main = $('#main'),
        $children = $('#children'),
        previous = null,
        count = 0;
    $('> div', '#children').click(function() {
        updateMainImage($('img', this));
    });
    centerImages();

    $('#back').click(function() {
        if (previous) {
            updateMainImage(previous);
        }
        return false;
    });

    function updateMainImage($this) {
        $mImg = $('img', $main);
        previous = $mImg;
        $img = $this.clone()
            .css({'z-index': 5,
                left: 0})
            .height(375)
            .hide();
        $main.append($img);
        $('.caption', $main).html('<span>' + $img.attr('alt') + '</span>');

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
        var text = false;
        if($children.has('p').length) {
            $children.html('');
            $children.removeClass('text');
            text = true;
        }

        var $images = $('> div', $children);
        var length = $images.length;
        count = 0;
        for(var i=0; i<data.length; i++) {
            var $div = $(document.createElement('div'))
                .css({left: $images.eq(i).css('left')})
                .hide();
            var $img = $(document.createElement('img'))
                .attr('src', data[i].image)
                .attr('rel', data[i].id)
                .attr('alt', data[i].title)
                .height(188);
            $div.append($img);
            $div.append('<div class="caption"><span>' + data[i].title + '</span></div>');
            $div.click(function() {
                updateMainImage($('img', this));
            });
            
            $children.append($div);
            $images.eq(i).fadeOut('slow', function() {
                ++count;
                $(this).remove();
            });
            $div.fadeIn('slow');
        }

        width = 0;
        $images = $('> div', $children);
        for(var j=data.length; j < length; j++) {
            width += $images.eq(j).width() + 12;
            $images.eq(j).remove();
        }
        
        if (width) {
            width /= 2;

            $images = $('> div', $children);
            for(var j=data.length - count; j <= length; j++) {
                var left = $images.eq(j).css('left');
                $images.eq(j).css('left', (parseInt(left.substring(0, left.length - 2)) + width) + 'px');
            }
        }

        if (text) {
            centerImages();
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

    function centerImages() {
        var width = 0;
        $('> div', $children).each(function() {
            width += $(this).width() + 12;
        });
        width = Math.ceil(($children.width() - width + 10) / 2);

        $('> div', $children).each(function() {
            $(this).css('left', width + 'px');
            width += $(this).width() + 12;
        });
    }
});</script>
</body>
</html>