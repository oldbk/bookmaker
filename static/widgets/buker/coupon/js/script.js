/**
 * Created by me on 13.07.2015.
 */
var $coupon = new Coupon();
$(function(){
    $coupon.run();
    $(window).on('user:ready', function(){
        $coupon.run();
        $(window).off('user:ready');
    });
    $(window).on('page:loaded', function(){
        $coupon.run();
    });

    $(document.body).on('touchstart click', '#coupon table.coupon-tabs td:not(.active)', function(e){
        DEBUG(_d, 'info', 'Coupon', 'Click tab');

        var $self = $(this);
        $self.closest('tr').find('td').removeClass('active');
        $self.addClass('active');

        if($self.data('type') == 'single') {
            $coupon.setSingle();
        } else {
            $coupon.setExpress();
        }
    });

    $(document.body).on('touchstart click', '#bet-list td div.ratio span.c-ratio', function(e){
        e.preventDefault();
        var $self = $(this);

        var event = $self.closest('tr').data('event');
        var value = $self.data('value');
        var type = $self.data('type');
        var num = $self.data('num');

        var flag = $self.hasClass('active');
        $('[data-event="'+event+'"]').find('span.c-ratio').removeClass('active');
        if(flag) {
            $coupon.removeEvent(event);
        } else {
            $coupon.addEvent(event, type, value, num);
        }

        $self.addClass('no-hover');
    });

    $(document.body).on('mouseout', '#bet-list td div.ratio span.c-ratio', function(e){
        e.preventDefault();
        $(this).removeClass('no-hover');
    });

    $(document.body).on('touchstart click', '#coupon .bet_block .bet_item .close', function(e){
        e.preventDefault();
        var $self = $(this);

        var event = $self.closest('.bet_item').data('event');
        $coupon.removeEvent(event);
    });

    $(document.body).on('keyup', '#coupon input.bet-price', function(){
        var event_id = $(this).closest('.bet_item').data('event');

        $coupon.setEventPrice(event_id, $(this).val());
    });

    $(document.body).on('touchstart click', '#coupon .buttons #bet-clear', function(){
        $coupon.clear();
    });

    $(document.body).on('touchstart click', '#coupon .buttons #bet-send', function(){
        $coupon.submit();
    });

    $(window).on('change:currency', function(){
        $coupon.clear();
    });
});

function Coupon()
{
    var _that = this;

    var options = {
        debug               : _d,
        list                : {},
        liga_list           : {},
        count               : 0,
        isExpress           : 0,
        express_sum         : 0.00,
        linkExpress         : null,
        linkSingle          : null,
        linkInfo            : null,
        currency            : null,
        storage             : null,
        $content            : null,
        $content_items      : null,
        $content_loader     : null,
        $content_summary    : null,
        $content_buttons    : null
    };

    var _prepareData = function() {
        DEBUG(options.debug, 'info', 'Coupon', '_prepareData');

        var cs                      = $.initNamespaceStorage('Coupon');
        options.storage             = cs.localStorage;
        options.currency            = $user.getCurrencyName();
        options.$content            = $('#coupon .content');
        options.$content_items      = $('#coupon .content #coupon-content-wrapp');
        options.$content_loader     = $('#coupon .content #coupon-loader-wrapp');
        options.$content_summary    = $('#coupon .content #coupon-summary-wrapp');
        options.$content_buttons    = $('#coupon .content #coupon-buttons-wrapp');
        options.list                = {};
        options.liga_list           = {};
        options.count               = 0;
        _that.empty();

        if(!options.storage.isEmpty('list') && options.storage.get('expire') > new Date().getTime()) {
            $.each(options.storage.get('list'), function(i, info){
                _addEventToCoupon(info);
            });
        }

        if(options.count > 0)
            _that.empty(true);
    };

    this.run = function() {
        _prepareData();
        $('#coupon').fBlock();

        _that.setSingle();
    };

    this.clear = function() {
        DEBUG(options.debug, 'info', 'Coupon', 'clear');

        options.storage.removeAll();
        $.each(options.list, function(event_id, info) {
            _removeEvent(event_id);
        });

        options.$content_buttons.html('');
        options.$content_summary.html('');
        options.$content_loader.html('');

        _that.empty();
    };

    this.submit = function() {
        DEBUG(options.debug, 'info', 'Coupon', 'clear');

        var data = options.isExpress ? _getExpressData() : _getSingleData();
        var callback = function(response) {
            if(response.update !== undefined) {
                $.each(response.update, function (i, info) {
                    $coupon.updateEvent(info);
                });
            }

            if(response.remove !== undefined) {
                $.each(response.remove, function(i, event_id){
                    $coupon.removeEvent(event_id);
                });
            }

            if(response.bet !== undefined && response.bet == 'success')
                $coupon.clear();
        };

        $ajax.send(_getSendLink(), {'data': data}, null, null, null, null, null, callback);
    };

    this.setEventPrice = function(event_id, price) {
        DEBUG(options.debug, 'info', 'Coupon', 'setEventPrice', event_id, price);

        if(options.isExpress)
            options.express_sum = price;
        else
            options.list[event_id]['price'] = price;
        _updateForm();
    };

    this.setIsExpress = function(value){
        DEBUG(options.debug, 'info', 'Coupon', 'setIsExpress', value);

        options.isExpress = value;

        return _that;
    };

    this.setSingle = function() {
        DEBUG(options.debug, 'info', 'Coupon', 'setSingle');

        _that.setIsExpress(false);

        if(options.count > 0) {
            options.$content_items.find('input').prop('readonly', false);
            _summary(true);
            _updateForm();
        }
    };

    this.setExpress = function() {
        DEBUG(options.debug, 'info', 'Coupon', 'setExpress');

        _that.setIsExpress(true);
        if(options.count > 0) {
            options.$content.find('input').prop('readonly', true);
            _summary();
            _updateForm();
        }
    };

    this.setLinks = function(linkExpress, linkSingle, linkInfo){
        DEBUG(options.debug, 'info', 'Coupon', 'setLinks', linkExpress, linkSingle, linkInfo);

        options.linkExpress = linkExpress;
        options.linkSingle = linkSingle;
        options.linkInfo = linkInfo;
    };

    this.setCurrency = function(currency) {
        DEBUG(options.debug, 'info', 'Coupon', 'setCurrency', currency);

        options.currency = currency;
    };

    this.addEvent = function(event, type, value, num) {
        DEBUG(options.debug, 'info', 'Coupon', 'addEvent', event, type, value, num);

        var data = {'Bet[event]': event, 'Bet[type]': type, 'Bet[value]': value, 'Bet[num]': num};
        if(options.count == 0)
            _that.empty(true);

        var callbackBefore = function() {
            $coupon.loader();
        };
        var callbackSuccess = function(response) {
            $coupon.loader(true);
            if(response.event_info !== undefined) {
                $coupon.addEventToCoupon(response.event_info);
            } else
                $coupon.empty();
        };
        var callbackError = function() {
            $coupon.loader(true);
            $coupon.empty();
        };

        $ajax.send(options.linkInfo, data, null, null, false, null, callbackBefore, callbackSuccess, callbackError);
    };

    this.updateEvent = function(info) {
        DEBUG(options.debug, 'info', 'Coupon', 'updateEvent', info);

        var event_id = info['id'];
        options.list[event_id]['ratio_value'] = info['value'];
        _updateForm();
    };

    this.removeEvent = function(event_id) {
        DEBUG(options.debug, 'info', 'Coupon', 'removeEvent', event_id);

        _removeEvent(event_id);
        if(options.count === 0)
            _that.empty();

        _updateForm();
    };

    this.addEventToCoupon = function(info) {
        DEBUG(options.debug, 'info', 'Coupon', 'addEventToCoupon', info);

        _addEventToCoupon(info);
        _updateForm();
    };

    this.loader = function(hide) {
        DEBUG(options.debug, 'info', 'Coupon', 'loader', hide);

        if(hide === true)
            options.$content_loader.html('');
        else
            options.$content_loader.html($('<div>', {'class': 'loader-block'}).append('<div class="bet-loader-element"></div>'));
    };

    this.empty = function(hide) {
        DEBUG(options.debug, 'info', 'Coupon', 'empty', hide);

        if(hide === true)
            options.$content_items.find('.empty-coupon').remove();
        else
            options.$content_items.html($('<div>', {'class': 'empty-coupon'}).append('Купон пустой'));
    };

    var _addEventToCoupon = function(info){
        DEBUG(options.debug, 'info', 'Coupon', '_addEventToCoupon', info);

        if(options.list[info['event_id']] !== undefined)
            _removeEvent(info['event_id']);

        if(options.count == 0) {
            _buttons();
            $('#coupon #coupon_block').collapse('show');

            if(options.isExpress)
                _summary();
        }

        $('tr[data-event="'+info['event_id']+'"] span[data-type="'+info['ratio_type']+'"]').addClass('active');
        var $item = _eventObj(info);
        var $liga = null;
        if(options.liga_list[info['liga_id']] !== undefined && options.liga_list[info['liga_id']] > 0) {
            $liga = options.$content_items.find('.bet_block[data-liga="' + info['liga_id'] + '"]');
            options.liga_list[info['liga_id']]++;
        } else {
            $liga = _ligaObj(info).appendTo(options.$content_items);
            options.liga_list[info['liga_id']] = 1;
        }

        if(info['price'] === undefined)
            info['price'] = 0.00;

        $liga.append($item);
        options.list[info['event_id']] = info;
        options.count++;
    };

    var _removeEvent = function(event_id) {
        DEBUG(options.debug, 'info', 'Coupon', '_removeEvent', event_id);

        var info = options.list[event_id];
        options.$content_items.find('.bet_item[data-event="'+event_id+'"]').remove();

        options.liga_list[info['liga_id']]--;
        if(options.liga_list[info['liga_id']] == 0)
            options.$content_items.find('.bet_block[data-liga="'+info['liga_id']+'"]').remove();

        options.count--;

        delete options.list[event_id];

        $('[data-event="'+event_id+'"]').find('span.c-ratio').removeClass('active');
        if(options.count == 0)
            _that.clear();
    };

    var _updateForm = function() {
        DEBUG(options.debug, 'info', 'Coupon', '_updateForm');

        if(options.isExpress) _updateExpress();
        else _updateSingle();


        var expires = new Date();
        expires.setMinutes(expires.getMinutes() + 10);
        options.storage.set('list', $.extend({}, options.list));
        options.storage.set('currency', options.currency);
        options.storage.set('expire', expires.getTime());
    };

    var _updateSingle = function() {
        DEBUG(options.debug, 'info', 'Coupon', '_updateSingle');

        $.each(options.list, function(event_id, info) {
            var $item = options.$content_items.find('.bet_item[data-event="'+event_id+'"]');
            $item.find('.ratio-value').html(info['ratio_value']);

            var calc = $.prepare(info['ratio_value']) * $.prepare( info['price']);
            if(info['price'] > 0)
                $item.find('input.bet-price').val(info['price']);

            $item.find('.coupon-res').html($.prepare(calc) + options.currency);
        });
    };

    var _updateExpress = function() {
        DEBUG(options.debug, 'info', 'Coupon', '_updateExpress');

        var ratio = 1;
        $.each(options.list, function(event_id, info) {
            var $item = options.$content_items.find('.bet_item[data-event="'+event_id+'"]');
            $item.find('.ratio-value').html(info['ratio_value']);

            if(info['price'] > 0)
                $item.find('input.bet-price').val(info['price']);

            $item.find('.coupon-res').html('0.00' + options.currency);

            ratio = ratio * info['ratio_value'];
        });

        var $bet_price = options.$content_summary.find('input.bet-price');
        ratio = $.prepare(ratio);
        options.express_sum = $.prepare($bet_price.val());
        options.$content_summary.find('.sum_ratio').html(ratio);
        var calc = ratio * options.express_sum;

        options.$content_summary.find('.coupon-res').html($.prepare(calc) + options.currency);

        var max = _getMaxBet();
        $bet_price
            .prop('title', 'max ' + max + options.currency)
            .attr('data-original-title', 'max ' + max + options.currency);
    };

    /**
     *
     * @returns {{items: {}, price: *}}
     * @private
     */
    var _getExpressData = function() {
        DEBUG(options.debug, 'info', 'Coupon', '_getExpressData');

        var data = {
            'items': {},
            'price': options.$content.find('#sum_block input.bet-price').val()
        };
        var eventInfo;
        $.each(options.list, function(event_id, info){
            eventInfo = {
                'event_id'      : event_id,
                'title'         : info['event_title'],
                'ratio_type'    : info['ratio_type'],
                'ratio_value'   : info['ratio_value']
            };
            data['items'][event_id] = eventInfo;
        });

        return data;
    };

    /**
     *
     * @returns {{}}
     * @private
     */
    var _getSingleData = function() {
        DEBUG(options.debug, 'info', 'Coupon', '_getSingleData');

        var data = {};
        var eventInfo;
        $.each(options.list, function(event_id, info){
            eventInfo = {
                'event_id'      : event_id,
                'title'         : info['event_title'],
                'ratio_type'    : info['ratio_type'],
                'ratio_value'   : info['ratio_value'],
                'price'         : options.$content_items.find('.bet_item[data-event="'+event_id+'"] input.bet-price').val()
            };
            data[event_id] = eventInfo;
        });

        return data;
    };

    var _ligaObj = function(info) {
        DEBUG(options.debug, 'info', 'Coupon', '_buildLiga', info);

        return $('<div>', {'class': 'bet_block', 'data-liga': info['liga_id']})
            .append(' <div class="bet_head">'+info['liga_title']+'</div>');
    };

    var _eventObj = function(info) {
        DEBUG(options.debug, 'info', 'Coupon', '_buildEvent', info);

        var $table = $('<table>', {'class': 'table list-event'})
            .append('<colgroup>' +
            '<col width="23px">' +
            '<col width="">' +
            '<col width="35px">' +
            '<col width="50px">' +
            '<col width="75px">' +
            '</colgroup>');

        $table
            .append($('<thead>').append('<tr class="bet_item_head"><th colspan="5">'+info['event_title']+'</th></tr>'));

        var $input = $('<input>', {
            'data-toggle'   : 'tooltip',
            'type'          : 'text',
            'title'         : 'max '+info['max_bet']+options.currency,
            'class'         : 'bet-price form-control double',
            'readonly'      : options.isExpress
        });

        $('<tbody>')
            .append(
                $('<tr>').append($('<td>').append($('<span>', {'class': 'glyphicon glyphicon-remove-circle close pointer'})))
                    .append($('<td>').append(info['ratio_type_string']))
                    .append($('<td>', {'class': 'ratio-value'}).append(info['ratio_value']))
                    .append($('<td>').append($input))
                    .append($('<td>', {'class': 'coupon-res'}).append('0.00'+options.currency))
                )
            .appendTo($table);

        return $('<div>', {'class': 'bet_item', 'data-event': info['event_id']})
            .append($('<div>', {'class': 'bet_item_ratio'}).append($table));
    };

    var _getSendLink = function() {
        return options.isExpress == true ? options.linkExpress : options.linkSingle;
    };

    var _getMaxBet = function() {
        DEBUG(options.debug, 'info', 'Coupon', '_getMaxBet');

        var max = 0;
        var max_bet = 0;
        $.each(options.list, function(event_id, info){
            max_bet = parseFloat(info['max_bet']);
            if(max == 0 || max > max_bet)
                max = max_bet;
        });

        return max;
    };

    var _summary = function(hide) {
        DEBUG(options.debug, 'info', 'Coupon', '_summary');

        if(hide === true)
            options.$content_summary.html('');
        else {
            if(options.$content_summary.find('.sum_block').length)
                return;

            var $block = $('<div>', {'id': 'sum_block'});
            var $table = $('<table>').appendTo($block);
            $table.append('<colgroup><col width="23px"><col width=""><col width="35px"><col width="50px"><col width="75px"></colgroup>');
            var $tbody = $('<tbody>'). appendTo($table);

            var $input = $('<input>', {
                'data-toggle': 'tooltip',
                'type': 'text',
                'class': 'bet-price form-control double'
            });

            var $tr = $('<tr>').appendTo($tbody);
            $tr
                .append('<td></td>')
                .append('<td></td>')
                .append('<td class="sum_ratio"></td>')
                .append($('<td>').append($input))
                .append('<td class="coupon-res">0.00'+options.currency+'</td>');

            options.$content_summary.html($block);
        }
    };

    var _buttons = function(hide) {
        DEBUG(options.debug, 'info', 'Coupon', '_buttons', hide);

        if(hide === true)
            options.$content_buttons.html('');
        else {
            var buttons = $('<div>', {'class': 'buttons'})
                .append('<a href="javascript:void(0);" class="label label-none" id="bet-clear">Очистить</a>')
                .append('<a href="javascript:void(0);" class="label label-active" id="bet-send">Сделать ставку</a>');
            options.$content_buttons.html(buttons);
        }

    };
}