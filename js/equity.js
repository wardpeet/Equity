var script = [];
// load jquery mobile or don't
if(navigator.userAgent.match(/(iPhone|iPod|iPad|Android|BlackBerry)/)) {    
    script[0] = '//code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js';
}
Modernizr.load({load: script});

$(function() {
    // disable ajax cache
    $.ajaxSetup({cache: true});
    var $img,
        $main = $('#main'),
        $children = $('#children'),
        previous = [],
        count = 0,
        current = null,
        height = 0;

    var tree = {
        updateMainImage: function($this) {
            $mImg = $('img', $main);
            previous.push($mImg);
            $img = $this.clone()
                .css({'z-index': 5,
                    left: 0})
                .height(375)
                .hide();
            $main.append($img);
            $('.caption', $main).html('<span>' + $img.attr('alt') + '</span>');
            $img.load(function() {
                $main.width($img.width()).height($img.height());
            });

            $mImg.fadeOut('slow');
            $img.fadeIn('slow', function() {
                $mImg.remove();
                ++count;
            });

            $.get('home/next/' + $.trim($this.attr('rel')), '', function(data) {
                if (data.next) {
                    tree.createNewImages(data.data);
                } else {
                    tree.createInfoPage(data.data);
                }
            }, 'json')
            .fail(function() {
                $('#back').click();
            });
        },
        createInfoPage: function(data) {
            $children.children().remove();

            if (data.type == 1) {
                app.slideImages(data);
            } else if (data.type == 2) {
                app.schemeImages(data);
            }
        },
        createNewImages: function(data) {
            if($children.attr('class')) {
                $children.html('');
                $children.removeAttr('class');
                $children.unbind();
            }
            $children.removeAttr('style');

            var $images = $children.children();
            var length = $images.length;
            count = 0;

            $images = $children.children();

            for(var i=0; i < data.length; i++) {
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

                if(i < length) {
                    $images.eq(i).fadeOut('slow', function() {
                        ++count;
                        $(this).remove();

                        if (count == data.length) {
                            for (var j=count; j<length; j++) {
                                $images.eq(j).remove();
                            }

                            // wait until jquery is done removing
                            var interval = setInterval(function() {
                                if (data.length == $children.children().length) {
                                    clearInterval(interval);
                                    tree.centerImages();
                                }
                            }, 50);
                        }
                    });
                }

                $div.fadeIn('slow', function() {
                    if (length < data.length) {
                        ++count;

                        if (count == data.length) {
                            // wait until jquery is done adding
                            var interval = setInterval(function() {
                                if (data.length == $children.children().length) {
                                    clearInterval(interval);
                                    tree.centerImages();
                                }
                            });
                        }
                    }
                });
            }
        },
        centerImages: function() {
            var width = 0;
            $children.children().each(function() {
                if ($(this).is(':visible')) {
                    $(this).css('left', width + 'px');
                    width += $(this).width() + 12;
                }
            });
            $children.width(width - 12);
            this.scaleImages();
        },
        scaleImages: function() {
            $body = $('body').width();
            if ($body < $children.width()) {
                var ratio = $body / $children.width();
                $children.children().each(function() {
                    $('img', this).height($('img', this).height() * ratio);
                    $(this).height($('img', this).height());
                });
                
                this.centerImages();
            } else {
                var h = $children.children().eq(0).height();
                if ((!height || height != h) && height < 188) {
                    var height = h * $body / $children.width();
                    $children.children().each(function() {
                        $('img', this).height((height > 188 ? 188 : height));
                        $(this).height($('img', this).height());
                    });

                    this.centerImages();
                }
            }
        }
    }

    var slider = {
        init: function($element) {
            $element.unbind('click');
            if(!$element.prev().is('div')) {
                $element.prev().unbind('click');
                $element.prev().bind('click', function() {
                    slider.doSlideLeft(current);
                });
            }
            if(!$element.next().is('div')) {
                $element.next().unbind('click');
                $element.next().bind('click', function() {
                    slider.doSlideRight(current);
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
        doSlideLeft: function(cur) {
            this.slideAway(cur.next());
            this.slideToSmall(cur, 0);
            cur = cur.prev();
            this.slideToBig(cur, 0);
            this.slideIn(cur.prev());

            this.init(cur);
            current = cur;
        },
        doSlideRight: function(cur) {
            this.slideAway(cur.prev());
            this.slideToSmall(cur, 0);
            cur = cur.next();
            this.slideToBig(cur, 0);
            this.slideIn(cur.next());

            this.init(cur);
            current = cur;
        },
        choiceBox: function(choices) {
            var $choice = $(document.createElement('div'))
                .addClass('choiceBox')
                .html('<h1>Keuze</h1>');
            $('body').append($(document.createElement('div')).attr('id', 'overlay'))
                .append($choice);
            
            for (var i in choices) {
                $a = $(document.createElement('a'))
                    .addClass('button')
                    .text(choices[i].text)
                    .attr('href', '#' + choices[i].text)
                    .attr('rel', choices[i].resources)
                    .click(function() {
                        $('#overlay').remove();
                        $(this).parent().remove();

                        var tag = this;
                        $choice.children('a').each(function() {
                            if(this != tag) {
                                var pictures = $(this).attr('rel').split('-');
                                var deleted = 0; // the dom tree changes so we deduct our pictures index too
                                for(var i in pictures) {
                                    $children.children().children().eq(parseInt(pictures[i]) - deleted).remove();
                                    ++deleted;
                                }
                            }
                        });
                        $children.children().children().eq(2).css('display', 'inline-block');
                        slider.init(current);

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
    
    var resourceHandler = {
        create: function(type, data) {
            var $return = null;
            switch(type) {
                case 1:
                    $return = $(document.createElement('img'))
                        .attr('src', data.value)
                        .attr('alt', data.title)
                        .attr('title', data.title);
                    break;
            }
            
            return $return;
        }
    }

    var app = {
        slideImages: function(data) {
            $('img:last', $main).height(200);
            $main.height(200).width($('img:last', $main).width() + 2); // + 2 is the border
            $children.addClass('slider');

            $children.append($(document.createElement('div')).addClass('small').html('&nbsp;'));
            for (var i=0; i < data.resources.length; i++) {
                var $img = resourceHandler.create(data.resources[i].type, data.resources[i])
                    .addClass('small');
                if (i > 1) {
                    $img.hide();
                }

                $children.append($img);
                
            }
            $children.append($(document.createElement('div')).addClass('small').html('&nbsp;'));
            $children.children().eq(1).removeClass('small');

            // if there is a choicebox use it
            if (data.choices.length) {
                slider.choiceBox(data.choices);
            }

            // swipe effects
            if ($('html').has('.ui-mobile')) {
                $children.bind('swiperight', function() {
                    if(!current.prev().is('div')) {
                        slider.doSlideLeft(current);
                    }
                });
                $children.bind('swipeleft', function() {
                    if(!current.next().is('div')) {
                        slider.doSlideRight(current);
                    }
                });
            }

            // center the box
            var childs = $children.children();
            $children.width(200 + 200 + 467 + 20 + 6); // 20 = margin and 6 are 6 borders

            current = $children.children().eq(1);
            slider.init(current);
        },
        schemeImages: function(data) {
            $('img:last', $main).height(100);
            $main.height(100).width($('img:last', $main).width() + 2); // 2 border
            $children.addClass('scheme');

            var maxWidth = 0;
            for (var i=0; i < data.resources.length; i++) {
                var $img = resourceHandler.create(data.resources[i].type, data.resources[i]);
                $children.append($img);

                $img.load(function() {
                    if($(this).height() > 250) {
                        $(this).height(250);
                    }
                    if($(this).width() > maxWidth) {
                        maxWidth = $(this).width();

                        $children.height(260)
                            .width(maxWidth);
                    }                    
                });

                if (i > 0) {
                    $img.hide();
                    $img.css({top: $(window).height()});
                }
            }

            scheme.init();
        }
    }

    $children.children().click(function() {
        tree.updateMainImage($('img', this));
    });
    $('img').load(function() {
        tree.centerImages();
    });

    $('#back').click(function() {
        var prev = previous.pop();
        if (prev) {
            tree.updateMainImage(prev);
            previous.pop();
        }
        return false;
    });

    $('#breadcrumb').bind('click tap', function(e) {
        if($('.breadcrumb-box').length) {
            $('.breadcrumb-box').remove();
            return false;
        }

        var $this = $(this);
        function createBox(data) {
            $ul = $(document.createElement('ul'))
                .addClass('breadcrumb-box')
                .css({top: ($this.height() + 25) +'px',
                    left: '5px'});

            for (var i in data) {
                $a = $(document.createElement('a')).attr('rel', data[i][0])
                    .html(data[i][1]); 
                $ul.append($(document.createElement('li')).append($a));
            }
            $('body').append($ul);
        }

        var data = [];
        for (var i in previous) {
            data[i] = [previous[i].attr('rel'), previous[i].attr('alt')];
        }
        i = (i != undefined ? i + 1 : 0);
        $img = $('img', $main);
        data[i] = [$img.attr('rel'), $img.attr('alt')];

        createBox(data);
        return false;
    });

    function sleep(delay) {
        var startTime = new Date();
        var endTime = null;
        do {
            endTime = new Date();
        } while ((endTime - startTime) < delay);
    }
});
