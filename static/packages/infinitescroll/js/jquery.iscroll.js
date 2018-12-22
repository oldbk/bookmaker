(function ( $ ) {
    'use strict';

    // Define the jscroll namespace and default settings
    $.iscroll = {
        defaults: {
            debug: false,
            padding: 50,
            loader: '<div class="loader"></div>',

            //page
            pageBlock: 'ul.pagination',
            pageSelector: 'li a[data-item="true"]',
            total_page: null,
            placeholder: '',
            page_place: 'page_place',

            //ajax
            dataType: 'html',
            beforeSend: null,
            afterSend: null,

            container: '.container'
        }
    };

    var iScroll = function($e, options){
        // Private vars and methods
        var _data = $e.data('iscroll'),
            _userOptions = (typeof options === 'function') ? { callback: options } : options,
            _options = $.extend({}, $.iscroll.defaults, _userOptions, _data || {}),
            _$window = $(window),
            _$document = $(document),
            _$container = $(_options.container),
            _$loader = $('<div class="iscroll-loader">').append(_options.loader),
            _$inner = $e.find('.iscroll-inner').length ? $e.find('.iscroll-inner') : null,

        // Wrap inner content, if it isn't already
            _wrapInnerContent = function() {
                if (_$inner === null) {
                    _$inner = $('<div>', {'style':'display:none;', 'class': 'iscroll-inner'});
                    _$inner.data('iscroll', {'next': 2});
                    $e.contents().wrapAll(_$inner);
                }
            },

        // Remove the iscroll behavior and data from an element
            _destroy = function() {
                _debug('info', 'iScroll', 'Destroy');
                $eventList.remove('scroll', 'iscroll:scroll');
                $e.removeData('iscroll');
            },

            _prepare = function() {
                if(_options.total_page === null) {
                    _options.total_page = parseInt($e.data('max'));
                }

                if(_options.placeholder == '') {
                    _options.placeholder = $e.find(_options.pageBlock).data('placeholder');
                }

                if(_options.total_page < 2)
                    return false;

                _wrapInnerContent();
                return true;
            },

            _observe = function() {
                var data = $e.data('iscroll'),
                    iTotalHeight = Math.ceil(_$window.scrollTop() + _$window.height() + _options.padding);

                if (!data.waiting && iTotalHeight >= _$document.height()) {
                    _debug('info', 'iScroll:', iTotalHeight - _$document.height(), 'from bottom. Loading next request...');
                    return _load();
                }

                return false;
            },

            _events = function() {
                $eventList.add('scroll', 'iscroll:scroll');
                $(window).off('iscroll:scroll').on('iscroll:scroll', function(){
                    _observe();
                });
                _observe();
            },

            _load = function() {
                var data = $e.data('iscroll');
                data.waiting = true;

                $.ajax({
                    url: _next(true),
                    dataType: _options.dataType,
                    beforeSend: function(){
                        if(_options.beforeSend !== null)
                            _options.beforeSend(link, $e);
                        else
                        $e.append(_$loader);
                    },
                    success: function(response) {
                        if(_options.afterSend !== null)
                            _options.afterSend(response, $e);
                        else
                            _$container.append(response);

                        data.waiting = false;
                        _afterLoad();
                    },
                    error: function(){
                        data.waiting = false;
                    }
                });
            },

            _afterLoad = function() {
                _$loader.remove();

                var data = _$inner.data('iscroll');
                data.next++;
                if(data.next > _options.total_page)
                    _destroy();
            },

        // Safe console debug - http://klauzinski.com/javascript/safe-firebug-console-in-javascript
            _debug = function(m) {
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
            },

            _next = function(){
                return _options.placeholder.replace(new RegExp(_options.page_place, 'g'), _$inner.data('iscroll').next);
            };

        this.destroy = function(msg){
            _destroy();
        };

        if(!_prepare())
            return false;

        _debug('info', 'iScroll', 'Start init.');
        $e.data('iscroll', $.extend({}, _data, {
            initialized: true,
            waiting: false
        }));

        _events();

        return this;
    };

    var page_infinity_arr = [];

    $.fn.iScroll = function( options ) {
        if(options === 'removeAll') {
            $.each(page_infinity_arr, function(i, $el){
                $el.destroy(options);
            });
            page_infinity_arr = [];

            return;
        }

        this.each(function() {
            var $this = $(this),
                data = $this.data('iscroll'), iscroll;

            // Instantiate jScroll on this element if it hasn't been already
            if (data && data.initialized) {
                return;
            }
            iscroll = new iScroll($this, options);
            if(iscroll !== false)
                page_infinity_arr.push(iscroll);
        });

        return page_infinity_arr;
    };

}( jQuery ));