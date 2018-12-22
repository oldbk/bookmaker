/**
 * Created by me on 09.10.2014.
 */
jQuery.prepare = function(value){
    if(isNaN(parseFloat(value)))
        return (0).toFixed(2);

    value = Math.floor(parseFloat((100 * value))) / 100;
    return value.toFixed(2);
};
Array.prototype.in_array = function(p_val) {
    try {
        for(var i = 0, l = this.length; i < l; i++)	{
            if(this[i] == p_val) {
                return true;
            }
        }
        return false;
    } catch (err) {
        console.log(err);
        return false;
    }
};

window.onerror = function(msg, url, line, col, error) {
    // Note that col & error are new to the HTML 5 spec and may not be
    // supported in every browser.  It worked for me in Chrome.
    var extra = !col ? '' : '\ncolumn: ' + col;
    extra += !error ? '' : '\nerror: ' + error;
    
    DEBUG(_d, 'error', 'Global', {
        'msg'   : msg,
        'url'   : url,
        'line'  : line,
        'extra' : extra
    });

    // TODO: Report this error via ajax so you can keep track
    //       of what pages have JS issues

    var suppressErrorAlert = true;
    // If you return true, then error alerts (like in older versions of
    // Internet Explorer) will be suppressed.
    return suppressErrorAlert;
};

function elementFocus (selector, scrollto)
{
    $(selector).scrollTo($(scrollto), 1000);
}
function reload()
{
    window.href.reload();
}
function closeModal()
{
    $('.modal-backdrop').remove();
    $('.modal').modal('hide');
}
function openCustom()
{
    closeModal();
    setTimeout(function(){ $('#customModal').modal('show'); }, 600);
}

function removeElement(selector)
{
    $(selector).remove();
}
function clearForm(){
    $('form .clear').val('');
}

function updatePage(link, data, loader)
{
    $ajax.send(link, data, null, null, loader);
}

function updateGrid(gridId, data, callback)
{
    if(data === undefined)
        data = {};
    if(callback === undefined)
        callback = function(){};


    var grid = $('#'+gridId) ;
    $.fn.yiiGridView.update(gridId, {
        data: data,
        complete:function(){
            grid.removeClass('grid-view-loading');
            callback();
        }
    });
}

$(function(){
    fixedTableHead();

    $(document.body).on('touchstart click', '.mass-select', function(){
        var $self = $(this);
        var $el = $(''+$self.data('selector')+'');
        if(!$self.closest('.mass-block').find('.mass-select:checked').length) {
            $el.find('a').addClass('disabled');
            return;
        }

        $el.find('a').removeClass('disabled');
    });

    $(document.body).on('touchstart click', '.mass-all-select', function(){
        var $self = $(this);
        var $el = $(''+$self.data('selector')+'');
        if(!$self.is(':checked')) {
            $self.closest('.mass-block').find('.mass-select').prop('checked', false);
            $el.find('a').addClass('disabled');
            return;
        }

        $self.closest('.mass-block').find('.mass-select').prop('checked', true);
        $el.find('a').removeClass('disabled');
    });
    $(document.body).on('touchstart click', '.spoiler-block .spoiler-text', function(){
        $(this).closest('.spoiler-block').find('.spoiler-hidden').toggle();
    });

    /*$(document.body).on('keydown', '.numbers', function(e){
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            (e.keyCode == 65 && e.ctrlKey === true) ||
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });*/

    /*$(document.body).on('click', '.m-menu .menu-item', function(){
        var $self = $(this);

        $('.left-menu li').removeClass('active');
        $self.addClass('active');
    });*/

    $(document.body).on('focus', '.datepicker', function(){
        $(this).datepicker({
            language   : 'ru',
            dateFormat : 'dd.mm.yy',
            autoclose  : true,
            todayBtn   : 'linked'
        });
    });
    /*$(document.body).on('focus', '.double', function(){
        doubleNumeric(this);
    });*/
    $(document.body).on('keypress', '.double', function(e){
        var $self = $(this);
        var value = $self.val();
        var theEvent = e || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
        if (key.length == 0) return;
        var regex = /^[0-9.,\b]+$/;
        var regex2 = /[.,]/g;
        var doubleDecimal = (key == ',' && regex2.test(value)) || (key == '.' && regex2.test(value));
        var decimalBegin = value.length == 0 && (key == ',' || key == '.');
        if (!regex.test(key) || doubleDecimal || decimalBegin) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    });
    /*$(document.body).on('focus', '.numbers', function(){
        numeric(this);
    });*/
    $(document.body).on('keypress', '.numbers', function(e){

        var $self = $(this);
        var theEvent = e || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
        if (key.length == 0) return;
        var regex = /^[0-9\b]+$/;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    });

    $(document.body).on('touchstart click', '.nav-bar-panel #balance a.bl:not(.label-active)', function(){
        var $self = $(this);

        var callback = function(response) {
            if(response.balance !== undefined)
                $user.updateBalance(response.balance);
        };

        $ajax.send($self.data('link'), null, null, null, null, null, null, callback);
        $self.blur();
    });

    $(document.body).on('mouseover', '[data-toggle="popover"]', function(){
        $(this).popover('show');
    });
    $(document.body).on('mouseover', '[data-toggle="tooltip"]', function(){
        $(this).tooltip('show');
    });
    $(document.body).on('mouseover', '.editable', function(){
        var $self = $(this);

        $self.editable({
            send: 'always',
            params: {'YII_CSRF_TOKEN': $ajax.getCSRF()},
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            success: function(data, config) {
                $ajax.checkResponse(data);
                if(data.errors)
                    return false;
            }
        });
    });

    $(window).on('page:loaded', function(){
        $.fn.iScroll('removeAll');
        $('.pager-infinity').iScroll({
            loader: '<div class="page-loader"><div class="loader"></div></div>',
            dataType  : 'json',
            afterSend : function(data, $this){
                var $block = $('#'+$this.data('to'));

                if(data.replaceList !== undefined) {
                    $.each(data.replaceList, function(i, item){
                        $block.append(item.view);
                    });
                }
            }
        });
    });
    
    $(window).on('page:loaded', function(){
        elementFocus('html', 'body');

        $.each($('.select2-tag'), function(i, el){
            console.log($(el).data("select2"));
            if($(el).data("select2") === undefined)
                $(el).select2({tags: true});
        });

        $.each($('.select2-simple'), function(i, el){
            console.log($(el).data("select2"));
            if($(el).data("select2") === undefined)
                $(el).select2();
        });

        $.each($('.select2-remote'), function(i, el){
            console.log($(el).data("select2"));
            if($(el).data("select2") === undefined)
                select2Remote($(el), $(el).data('link'));
        });
    });

    $(window).trigger('page:loaded');
});

function doubleNumeric(selector)
{
    var min = '0.00';
    var max = '100.00';

    var $el = $(selector);
    if($el.length == 0)
        return;

    $.each($el, function(i, item){
        var $item = $(item);
        if($item.data('min') !== undefined && $item.data('min') !== null)
            min = $item.data('min');
        if($item.data('max') !== undefined && $item.data('max') !== null)
            max = $item.data('max');

        var value = parseFloat($item.val());
        if(isNaN(value) || value < min)
            $item.val(min);

        $item.autoNumeric('init', {'vMin':min,'vMax':max, 'aSep':'', 'wEmpty':min});
    });
}

function numeric(selector)
{
    var min = '0';
    var max = '10000000';

    var $el = $(selector);
    if($el.length == 0)
        return;

    if($el.data('min') !== undefined && $el.data('min') !== null)
        min = $el.data('min');
    if($el.data('max') !== undefined && $el.data('max') !== null)
        max = $el.data('max');

    var value = parseInt($el.val());
    if(isNaN(value) || value < min)
        $el.val(min);

    $el.autoNumeric('init', {'vMin':min,'vMax':max, 'aSep':'', 'wEmpty':min});
}

function elementTrigger(selector, event)
{
    $(selector).trigger(event);
}
function select2()
{
    var $obj = $('.select2');
    $.each($obj, function(i, el){
        if($(el).data("select2") === undefined)
            $(el).select2();
    });
}
function select2tag()
{
    var $obj = $('.select2-tag');
    $.each($obj, function(i, el){
        if($(el).data("select2") === undefined)
            $(el).select2({tags: true});
    });
}
function select2Remote($obj, link)
{
    $obj.select2({
        ajax: {
            url: link,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, page) {
                return {
                    results: data.items
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });
}

function formatRepo (repo) {
    return repo.label;
}

function formatRepoSelection (repo) {
    return repo.label;
}

function loaderLine(selector, line)
{
    var $header = $('[data-event-for="line-'+line+'"]');
    $header.attr('data-load', true).attr('data-type', false);
    $header.click(function(e){
        e.preventDefault();

        var $self = $(this);
        $('[data-event-in="'+$self.data('event-for')+'"]').toggle();
    });
}

function fixedTableHead(selector)
{
    if(selector === undefined)
        selector = '.head-sticker';
    //$(selector).attr('data-sticker', selector).fixMe();
}

function DEBUG(isDebug, m) {
    if(_d) {
        console.log(isDebug);
    }
    if (isDebug && typeof console === 'object' && (typeof m === 'object' || typeof console[m] === 'function')) {
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
            console[m].apply(console, Array.prototype.slice.call(arguments, 2));
        }
    }
};