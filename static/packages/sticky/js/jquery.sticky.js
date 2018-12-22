(function($) {
    $.fn.fixTable = function() {
        return this.each(function() {
            var $this = $(this),
                $t_fixed;
            if($this.closest('.fixed-table-container').length > 0)
                return;
            function init() {
                $this.wrap('<div class="fixed-table-container" style="position: relative;" />');
                $t_fixed = $this.clone();

                $t_fixed.find('colgroup').remove();
                $t_fixed.css({'z-index': 101});
                $t_fixed.find("tbody").remove().end().addClass("head-sticker-fixed").insertBefore($this);
                resizeFixed();
            }
            function resizeFixed() {
                $t_fixed.find("th").each(function(index) {
                    $(this).css("width",$this.find("th").eq(index).outerWidth()+"px");
                });
            }
            function scrollFixed() {
                var offset = $(this).scrollTop(),
                    tableOffsetTop = $this.offset().top,
                    tableOffsetBottom = tableOffsetTop + $this.height() - $this.find("thead").height();

                console.log(offset, tableOffsetTop);
                if(offset < tableOffsetTop || offset > tableOffsetBottom)
                    $t_fixed.hide();
                else if(offset >= tableOffsetTop && offset <= tableOffsetBottom && $t_fixed.is(":hidden"))
                    $t_fixed.show();
            }
            $(window).resize(resizeFixed);
            $(window).scroll(scrollFixed);
            init();
        });
    };
})(jQuery);

(function($){
    'use strict';

    $.fblock = {
        defaults: {
            debug: true
        }
    };

    var FBlock = function($e, options){
        var _data = $e.data('fblock'),
            _options = $.extend({}, $.iscroll.defaults, options, _data || {}),
            _$window = $(window);

        var _wrapInnerContent = function() {
            if (!$e.find('.fblock-inner').length) {
                $e.contents().wrapAll($('<div>', {'class': 'fblock-inner'}));
            }
        };

        var _events = function() {
            $eventList.add('scroll', 'sticky:scroll');
            $(window).off('sticky:scroll').on('sticky:scroll', function(){
                _observe();
            });

            _observe();
        };

        var _observe = function(){
            _wrapInnerContent();

            if($e.offset().top < _$window.scrollTop()) {
                $e.find('.fblock-inner').addClass('fblock');
            } else {
                $e.find('.fblock-inner').removeClass('fblock');
            }
        };

        var _debug = function(m) {
            if (_options.debug && typeof console === 'object' && (typeof m === 'object' || typeof console[m] === 'function')) {
                if (typeof m === 'object') {
                    var args = [];
                    for (var sMethod in m) {
                        if (typeof console[sMethod] === 'function') {
                            args = (m[sMethod].length) ? m[sMethod] : [m[sMethod]];
                            console[sMethod].apply(console, args);
                        } else {
                            console.log.apply(console, args);
                        }
                    }
                } else {
                    console[m].apply(console, Array.prototype.slice.call(arguments, 1));
                }
            }
        };

        $e.data('fblock', $.extend({}, _data, {
            initialized: true
        }));

        _events();
    };

    var page_fblock_arr = [];
    $.fn.fBlock = function( options ) {
        this.each(function() {
            var $this = $(this),
                data = $this.data('fblock'), fblock;

            // Instantiate jScroll on this element if it hasn't been already
            if (data && data.initialized) {
                return;
            }
            fblock = new FBlock($this, options);
            if(fblock !== false)
                page_fblock_arr.push(fblock);
        });

        return page_fblock_arr;
    };

})(jQuery);