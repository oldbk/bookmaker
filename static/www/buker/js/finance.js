/**
 * Created by me on 19.02.2015.
 */
$(function(){
    getLastOperation();
});

function getLastOperationIn()
{
    $('#finance-in a[data-for="finance-in"]').trigger('click');
}

function getLastOperationOut()
{
    $('#finance-out a[data-for="finance-out"]').trigger('click');
}

function getLastOperation()
{
    $('#finance-in a[data-for="finance-in"], #finance-out a[data-for="finance-out"]').trigger('click');
}

function clearFinanceIn()
{
    var $el1 = $('#finance-part').find('[name="ekrIn[price]"]');
    var $el2 = $('#finance-part').find('[name="krIn[price]"]');
    var $el3 = $('#finance-part').find('[name="goldIn[price]"]');

    $el1.val($el1.data('min'));
    $el2.val($el2.data('min'));
    $el3.val($el3.data('min'));

    getLastOperationIn();
}

function clearFinanceOut()
{
    var $el1 = $('#finance-part').find('[name="ekrOut[price]"]');
    var $el2 = $('#finance-part').find('[name="krOut[price]"]');
    var $el3 = $('#finance-part').find('[name="goldOut[price]"]');

    $el1.val($el1.data('min'));
    $el2.val($el2.data('min'));
    $el3.val($el3.data('min'));

    getLastOperationOut();
}