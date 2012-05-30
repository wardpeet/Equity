var script = [];
if(navigator.userAgent.match(/(iPhone|iPod|iPad|Android|BlackBerry)/)) {
    $('head').append('<link href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" rel="stylesheet" />');
    
    script[0] = '//code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js';
}
Modernizr.load({load: script});

$(function() {
    var $img,
        $main = $('#main'),
        $children = $('#children'),
        previous = null,
        count = 0,
        current = null;

    var tree = {
        updateMainImage: function($this) {
            $mImg = $('img', $main);
            previous = $mImg;
            $img = $this.clone()
                .css({'z-index': 5,
                    left: 0})
                .height(375)
                .hide();
            $main.append($img);
            $('.caption', $main).html('<span>' + $img.attr('alt') + '</span>');
            $main.width($img.width()).height($img.height());

            $mImg.fadeOut('slow');
            $img.fadeIn('slow', function() {
                $mImg.remove();
                ++count;
            });

            $.get(siteurl + '/home/next', 'category=' + $this.attr('rel'), function(data) {
                if (data.next) {
                    tree.createNewImages(data.data);
                } else {
                    tree.createInfoPage(data.data);
                }
            }, 'json');
        },
        createInfoPage: function(data) {
            $children.children().remove();

            if (data.type == 1) {
                slideImages(data);
            } else if (data.type == 2) {
                schemeImages(data);
            }
        },
        createNewImages: function(data) {
            if($children.attr('class')) {
                $children.html('');
                $children.removeClass();
            }

            var $images = $children.children();
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
                    tree.updateMainImage($('img', this));
                });

                $children.append($div);
                $images.eq(i).fadeOut('slow', function() {
                    ++count;
                    $(this).remove();
                });
                $div.fadeIn('slow');
            }

            $images = $children.children();
            for(var j=data.length; j < length; j++) {
                $images.eq(j).remove();
            }

            if($children.children().length == data.length) {
                this.centerImages();
            } else {
                var intv = setInterval(function() {
                    if($children.children().length == data.length) {
                        clearInterval(intv);
                        tree.centerImages();
                    }
                    
                }, 5);
            }
        },
        centerImages: function() {
            var width = 0;
            $children.children().each(function() {
                width += $(this).width() + 12;
            });
            width = Math.ceil(($children.width() - width + 10) / 2);

            $children.children().each(function() {
                $(this).css('left', width + 'px');
                width += $(this).width() + 12;
            });
        }
    }

    var slider = {
        init: function($element) {
            $element.unbind('click');
            if(!$element.prev().is('div')) {
                $element.prev().unbind('click');
                $element.prev().bind('click', function() {
                    doSlideLeft();
                });
            }
            if(!$element.next().is('div')) {
                $element.next().unbind('click');
                $element.next().bind('click', function() {
                    doSlideRight();
                });
            }
        },
        slideAway: function($element) {
            this.slide($element, true);
        },        
        slideIn: function($element) {
            this.slide($element, false);
        },
        slide: function($element, hide) {
            $element.animate({
                opacity: 0.4
            }, 'fast', 'linear', function() {
                if (hide) {
                    $(this).hide();
                } else {
                    this.style.display = 'inline-block';
                }
            });
        },
        slideToBig: function($element, width) {
            $element.show().animate({
                opacity: 1,
                marginLeft: (width + 10) + 'px',
                width: '467px',
                marginRight: '10px'
            }, 'fast', 'linear', function() {
                $(this).removeClass('small');
            });
        },
        slideToSmall: function($element, width) {
            $element.show().animate({
                opacity: 0.4,
                marginLeft: width + 'px',
                width: '200px',
                marginRight: 0
            }, 'fast', 'linear', function() {
                $(this).addClass('small');
            });
        },
        choiceBox: function(choices) {
            var $choice = $(document.createElement('div'))
                .addClass('choiceBox')
                .html('<h1>Keuze</h1>');
            $('body').append($(document.createElement('div')).attr('id', 'overlay'))
                .append($choice);
            
            for (var i in choices.list) {
                $a = $(document.createElement('a'))
                    .addClass('button')
                    .text(choices.list[i])
                    .attr('href', '#' + choices.list[i])
                    .attr('rel', choices.images)
                    .click(function() {
                        $('#overlay').remove();
                        $(this).parent().remove();

                        var tag = this;
                        $choice.children('a').each(function() {
                            if(this != tag) {
                                var pictures = $(this).attr('rel').split('-');
                                for(var j=parseInt(pictures[0]) + 1; j <= parseInt(pictures[1]) + 1; j++) {
                                    $children.children().eq(0).children().eq(j).remove();
                                }
                            }
                        })

                        return false;
                    });
                $choice.append($a);
            }

            $choice.css({top: ($(window).height() - $choice.height()) / 2 + 'px',
                left: ($(window).width() - $choice.width()) / 2 + 'px'});
            
        }
    };
    
    var scheme = {
        init: function() {
            $children.click(function() {
                $img = $('img:hidden:first');
                if ($img.length) {
                    $img.show().animate({
                        top: $children.height() + 'px'
                    }, 'fast', 'linear', function() {
                        $('img:hidden').css({top: $(window).attr("scrollHeight")});
                    });
                    $('html,body').animate({scrollTop: $(document).height()});
                    $children.height($children.height() + 260);
                }
            });
        }
    }

    $children.children().click(function() {
        tree.updateMainImage($('img', this));
    });
    tree.centerImages();

    $('#back').click(function() {
        if (previous) {
            tree.updateMainImage(previous);
        }
        return false;
    });

    function slideImages(data) {
        $('img', $main).height(200);
        $main.height(200).width($('img', $main).width());
        $children.addClass('slider');

        // add images to slider
        $div = $(document.createElement('div'));
        $children.append($div);

        $children = $div;
        $children.append($(document.createElement('div')).addClass('small').html('&nbsp;'));
        for (var i=0; i < data.pictures.length; i++) {
            var $img = $(document.createElement('img'))
                .attr('src', data.pictures[i].image)
                .attr('alt', data.pictures[i].title)
                .addClass('small');
            if (i > 1) {
                $img.hide();
            }

            $children.append($img);
        }
        $children.append($(document.createElement('div')).addClass('small').html('&nbsp;'));
        $children.children().eq(1).removeClass('small');

        // swipe effects
        if ($('html').has('.ui-mobile')) {
            $children.bind('swipeleft', function() {
                doSlideLeft();
            });
            $children.bind('swiperight', function() {
                doSlideRight();
            });
        }

        current = $children.children().eq(1);
        slider.init(current);
        $children = $children.parent();
    }    
    function doSlideLeft() {
        slider.slideAway(current.next());
        slider.slideToSmall(current, 0);
        current = current.prev();
        slider.slideToBig(current, 0);
        slider.slideIn(current.prev());

        slider.init(current);
    }    
    function doSlideRight() {
        slider.slideAway(current.prev());
        slider.slideToSmall(current, -10);
        current = current.next();
        slider.slideToBig(current, 0);
        slider.slideIn(current.next());

        slider.init(current);
    }

    function schemeImages(data) {
        $('img', $main).height(100);
        $main.height(100).width($('img', $main).width());
        $children.addClass('scheme');

        var maxWidth = 0;
        for (var i=0; i < data.pictures.length; i++) {
            var $img = $(document.createElement('img'))
                .attr('src', data.pictures[i].image)
                .attr('alt', data.pictures[i].title);

            $children.append($img);

            if($img.height() > 250) {
                $img.height(250);
            }
            if($img.width() > maxWidth) {
                maxWidth = $img.width();
            }            
            if (i > 0) {
                $img.hide();
                $img.css({top: $(window).height()});
            }
        }
        $children.height(260)
            .width(maxWidth);
        
        scheme.init();
    }
});