/**
 * Created by me on 08.08.2015.
 */

var $user = new User();
function User()
{
    var _that = this;
    var _balanceType;
    var _currencyName;

    this.setOptions = function(b_type, c_name) {
        _balanceType = b_type;
        _currencyName = c_name;
        $(window).trigger('user:ready');
    };

    this.setBalanceType = function(type) {
        _balanceType = type;
    };

    this.getBalanceType = function() {
        return _balanceType;
    };

    this.setCurrencyName = function(name) {
        _currencyName = name;
    };

    this.getCurrencyName = function() {
        return _currencyName;
    };

    this.updateBalance = function(balance)
    {
        var kr = balance.kr, ekr = balance.ekr, gold = balance.gold, active = balance.active;
        var $block = $('.balance');
        $block.find('.kr').html(kr);
        $block.find('.ekr').html(ekr);
        $block.find('.gold').html(gold);

        $block.find('a.bl').removeClass('label-none label-active').addClass('label-none');
        $block.find('a[data-type="'+active+'"]').removeClass('label-none').addClass('label-active');
        _that.setBalanceType(active);
        _that.setCurrencyName(balance.name);
    };
}