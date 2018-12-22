/**
 * Created by Ice on 13.07.14.
 */
$.fn.exists = function(){return this.length>0;};
jQuery.loadScript = function (url, callback) {
    $.getScript( url )
        .done(function( script, textStatus ) {
            var fn = jQuery.parseJSON(callback);
            $ajax.runJS(fn);
        })
        .fail(function( jqxhr, settings, exception ) {

        });
    /*jQuery.ajax({
        url      : url,
        dataType : 'script',
        async    : true,
        cache    : true,
        success  : function(){
            eval(callback);
        }
    });*/
};

jQuery.loadStyle = function (url, callback) {
    if (document.createStyleSheet){
        document.createStyleSheet(url);
    }
    else {
        $("head").append($("<link rel='stylesheet' href='"+url+"' type='text/css' media='screen' />"));
    }
};


var $ajax = new AJAX();
var HistoryCallback = {};
$(function(){
    try {
        var History = window.History;
        History.Adapter.bind(window, 'statechange', function() {
            var state = History.getState();
            var index = state.data._index;

            if(HistoryCallback[index] !== undefined) {
                $.each(HistoryCallback[index], function(el, fn){
                    fn.apply();
                });
            } else
                window.location.reload();
        });
        $ajax.flag = true;
    } catch (ex) {
        console.log(ex);
    }

    $ajax.events();

    $( document ).ajaxError(function(error, response) {
        //console.log(error, response);
        if(response.status == 500)
            $ajax.send('/error.html', {'status': response.status});
        else
            $ajax.checkResponse(response.responseText);
        $ajax.hideLoader();
    });
});


function AJAX()
{
    var _that = this;
    var _CSRF = null;
    var _debug = true;
    var _inProcess = false;
    this.loadedJS = [];
    this.loadedCSS = [];

    this.flag = false;

    this.setLoaded = function(arr){
        _that.loadedJS = arr;
    };
    this.setLoadedCSS = function(arr){
        _that.loadedCSS = arr;
    };

    this.checkResponse = function(response){
        try {
            response = jQuery.parseJSON(response);
        } catch (e) {
        }

        $ajax.afterValidate(response);

        if(response.replaceList !== undefined) {
            $.each(response.replaceList, function(i, item){
                $(item.selector).replaceWith(item.view);
                $(item.selector).find("table.list tbody tr:nth-child(odd), table.list-event tbody tr:nth-child(odd)").addClass("odd");
                $(item.selector).find("table.list tbody tr:nth-child(even), table.list-event tbody tr:nth-child(even)").addClass("even");
            });
        }
        if(response.htmlList !== undefined) {
            $.each(response.htmlList, function(i, item){
                $(item.selector).html(item.view);
                $(item.selector).find("table.list tbody tr:nth-child(odd), table.list-event tbody tr:nth-child(odd)").addClass("odd");
                $(item.selector).find("table.list tbody tr:nth-child(even), table.list-event tbody tr:nth-child(even)").addClass("even");
            });
        }

        if(response.runJS !== undefined)
            $ajax.runJS(response.runJS);

        if(response.redirectLink !== undefined)
            window.location = response.redirectLink;

        if(response.pageTitle !== undefined)
            $('title').html(response.pageTitle);

        if(response.menu !== undefined) {
            $.each(response.menu, function(id, html){
                if(html == false)
                    $('#'+id).html('');
                else
                    $('#'+id).html(html);
            });
        }
    };

    this.beforeValidate = function(){

    };

    this.afterValidate = function(data){
        if(data === null || data === undefined)
            return true;

        if(data.errors !== undefined) {
            $.each(data.errors, function(type, errorList) {
                $.each(errorList, function(field, text){
                    _that.message(type, text);
                });
            });
            return false;
        } else {
            if(data.close !== undefined && data.close === true)
                $.fancybox.close();

            if(data.messages !== undefined) {
                $.each(data.messages, function(type, message){
                    $.each(message, function(i, text){
                        _that.message(type, text);
                    });
                });
            }

            return true;
        }
    };

    this.setCSRF = function(value){
        _CSRF = value;
    };
    this.getCSRF = function(){
         return _CSRF;
    };


    this.message = function(type, text){
        var n = noty({
            text: text,
            layout: 'topRight',
            type: type,
            theme: 'relax',
            timeout: 10000
        });
    };

    this.showLoader = function(loader){
        loader = (loader === undefined || loader === null) ?  _that.getDefaultLoader() : loader;
        loader.fadeIn(500);
    };

    this.hideLoader = function(loader) {
        loader = (loader === undefined || loader === null) ?  _that.getDefaultLoader() : loader;
        loader.fadeOut(500);
    };
    this.getDefaultLoader = function(){
        return $('#page-dark');
    };

    var _confirmPrepare = function($obj)
    {
        var text = $obj.data('confirm-text');
        if($obj.data('added') !== undefined) {
            switch  ($obj.data('added')) {
                case 'value':
                    var value = $('#'+$obj.data('added-value-id')).val();
                    console.log(value, text);
                    text = text.replace('%val%', value);
                    break;
            }
        }
        return confirm(text);
    };

    this.events = function(){
        $(document.body).on('touchstart click', 'form.ajax [data-type="ajax-submit"]', function(event){
            event.preventDefault();

            var $self = $(this);
            if($self.data('confirm') !== undefined && !_confirmPrepare($self))
                return false;

            var loader = ($self.data('loader') !== undefined) ? $self.closest('.loader-block').find('.page-dark-element') : null;
            var link = ($self.data('link') !== undefined) ? $self.data('link') : null;

            var isHistory;
            if($self.data('history') !== undefined)
                isHistory = true;
            _submitForm($('#'+$self.data('for')), link, loader, isHistory);
        });
        $(document.body).on('submit', 'form.ajax', function(event){
            event.preventDefault();

            var $self = $(this).find('[data-type="ajax-submit"]');
            if($self.data('confirm') !== undefined && !_confirmPrepare($self))
                return false;

            var loader = ($self.data('loader') !== undefined) ? $self.closest('.loader-block').find('.page-dark-element') : null;
            var link = ($self.data('link') !== undefined) ? $self.data('link') : null;

            var isHistory;
            if($self.data('history') !== undefined)
                isHistory = true;
            _submitForm($('#'+$self.data('for')), link, loader, isHistory);
        });

        $(document.body).on('touchstart click', '[data-type="ajax"]', function(event){
            event.preventDefault();

            var $self = $(this);
            var params = {'clicker': $self};

            if($self.data('confirm') !== undefined && !confirm($self.data('confirm-text')))
                return false;

            var historyLink = null;
            var link = $self.attr('href');
            if($self.data('link') !== undefined)
                link = $self.data('link');

            if($self.data('history') !== undefined)
                historyLink = link;

            var loader = null;
            if($self.data('loader') !== undefined)
                loader = $self.closest('.loader-block').find('.page-dark-element');

            _that.send(link, {}, null, null, loader, historyLink, null, null, null, null, params);
            $self.blur();
        });

        $(window).on('change:currency', function(){
            $ajax.send(_last_page);
        });
    };

    //data-modal-selector
    var _submitForm = function($form, link, loader, isHistory){
        var data = _prepareFormData($form.find('.field').serializeArray());

        link = (link === undefined || link === null) ? $form.attr('action') : link;
        var historyLink = null;
        if(isHistory !== undefined) {
            historyLink = link;
            if($form.attr('method') == 'get') {
                if (!historyLink.match(/\?/))
                    historyLink += '?';
                $.each(data, function (name, value) {
                    historyLink += name + '=' + value + '&';
                });
            }
        }

        _that.send(link, data, null, $form.attr('method'), loader, historyLink);
    };

    this.runJS = function(params){
        if(params[0] === undefined)
            params = {0: params};

        $.each(params, function(i, call){
            try {
                var fnstring = call.name;
                var fnparams = call.params;

                var fn;
                if(fnstring.indexOf('.') < 1)
                    fn = window[fnstring];
                else {
                    var arr = fnstring.split('.');
                    if(window[arr[0]] !== undefined && window[arr[0]][arr[1]] !== undefined)
                        fn = window[arr[0]][arr[1]];
                }

                if (typeof fn === "function") fn.apply(null, fnparams);
            } catch(e) {
                console.log(e);
            }
        });

    };

    var _last_page = null;
    this.send = function(link,data,dataType,requestType,loader,historyUrl,callbackBefore,callbackSuccess,callbackError,callbackHistory,params){
        data             = (data              === undefined || data              === null ) ? {}                       : data;
        dataType         = (dataType          === undefined || dataType          === null ) ? 'json'                   : dataType;
        requestType      = (requestType       === undefined || requestType       === null ) ? 'post'                   : requestType;
        loader           = (loader            === undefined || loader            === null ) ? _that.getDefaultLoader() : loader;
        historyUrl       = (historyUrl        === undefined || historyUrl        === null ) ? ''                       : historyUrl;
        callbackBefore   = (callbackBefore    === undefined || callbackBefore    === null ) ? function(){}             : callbackBefore;
        callbackSuccess  = (callbackSuccess   === undefined || callbackSuccess   === null ) ? function(){}             : callbackSuccess;
        callbackError    = (callbackError     === undefined || callbackError     === null ) ? function(){}             : callbackError;
        callbackHistory  = (callbackHistory   === undefined || callbackHistory   === null ) ? function(){}             : callbackHistory;
        params           = (params            === undefined || params            === null ) ? {}                       : params;


        if(_inProcess === true) {
            setTimeout(function(){
                $ajax.send(link,data,dataType,requestType,loader,historyUrl,callbackBefore,callbackSuccess,callbackError,callbackHistory,params);
            }, 1000);
            return;
        }

        if(historyUrl != '' && historyUrl != window.location.pathname) {
            if(!History || !History.pushState || !window.history.pushState || !_that.flag) {
                window.location = link;
                return;
            }

            var sendRequest = function() { $ajax.send(link,data,dataType,requestType,loader,null,callbackBefore,callbackSuccess,callbackError,null,params); };
            HistoryCallback[History.getCurrentIndex()] = {
                'callbackHistory'  : callbackHistory,
                'sendRequest'      : sendRequest
            };

            History.pushState({'_index' : History.getCurrentIndex()}, null, historyUrl);
            return;
        }

        if(requestType == 'post')
            data['YII_CSRF_TOKEN'] = _CSRF;

        _that.clearAjaxWin();
        $.ajax({
            url         : link,
            data        : data,
            type        : requestType,
            dataType    : dataType,
            xhrFields: {
                withCredentials: true
            },
            beforeSend  : function(){
                _inProcess = true;

                if(loader !== false)
                    _that.showLoader(loader);

                callbackBefore();
                return _that.beforeValidate();
            },
            success     : function(response){
                _inProcess = false;

                callbackSuccess(response);

                if(response.error !== undefined && response.error == true) {
                    callbackError();
                }

                if(loader !== false)
                    _that.hideLoader(loader);
            },
            error       : function() {
                callbackError();

                _inProcess = false;
            }
        }).done(function(data){
            if(_d) {
                console.log('DONE', data);
            }
            var _ajax = this;
            if(data)
                _that.checkResponse(data);
            if(data.triggers !== undefined) {
                $.each(data.triggers, function(i, event){
                    $(window).trigger(event);
                    if(event == 'page:loaded') {
                        _last_page = _ajax.url;
                    }
                });
            }
        });
    };

    var _prepareFormData = function(data) {
        var returned = {};
        $.each(data, function(i, info){
            if(info['name'].indexOf('[]') > 0) {
                if(returned[info['name']] === undefined)
                    returned[info['name']] = [];
                returned[info['name']].push(info['value']);
            } else
                returned[info['name']] = info['value'];
        });

        return returned;
    };

    this.prepareFormData = function(data) {
        return _prepareFormData(data);
    };

    var originEncodeURIComponent = window.encodeURIComponent;
    this.ajaxWin = function()
    {
        var transAnsiAjaxSys = [];
        for (var i = 0x410; i <= 0x44F; i++)
            transAnsiAjaxSys[i] = i - 0x350;
        transAnsiAjaxSys[0x401] = 0xA8;
        transAnsiAjaxSys[0x451] = 0xB8;
        window.encodeURIComponent = function(str)
        {
            var ret = [];
            for (var i = 0; i < str.length; i++)
            {
                var n = str.charCodeAt(i);
                if (typeof transAnsiAjaxSys[n] != 'undefined')
                    n = transAnsiAjaxSys[n];
                if (n <= 0xFF)
                    ret.push(n);
            }
            return escape(String.fromCharCode.apply(null, ret));
        }
    };

    this.clearAjaxWin = function()
    {
        window.encodeURIComponent = originEncodeURIComponent;
    };

    this.loadScript = function(url_list) {
        $.each(url_list, function(url, callback) {
            if(!_that.loadedJS.in_array(url)) {
                _that.loadedJS.push(url);
                $.loadScript(url, callback);
            }
        });
    };

    this.loadStyle = function(url_list) {
        $.each(url_list, function(i, url) {
            if(!_that.loadedCSS.in_array(url)) {
                _that.loadedCSS.push(url);
                $.loadStyle(url);
            }
        });
    };
}