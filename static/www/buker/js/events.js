/**
 * Created by me on 18.02.2015.
 */
$(function(){
    $(document.body).on('change', '.result-control #SportEvent_have_result', function() {
        var $self = $(this);
        var flag = false;
        if(!$self.is(':checked'))
            flag = true;

        $self.closest('.result-control').find('.box input[type="text"]').attr('disabled', flag);
        $('.result-control #SportEventResult_is_cancel').attr('disabled', flag).attr('checked', false);
    });
    $(document.body).on('change', '.result-control #SportEventResult_is_cancel', function() {
        var $self = $(this);
        var flag = false;

        if($self.is(':checked'))
            flag = true;

        $self.closest('.result-control').find('.box input[type="text"]').attr('disabled', flag);
    });
    checkControl();
});

function checkControl() {
    $('.result-control #SportEvent_have_result').trigger('change');
}