/**
 * Created by ice on 16.09.2015.
 */
$(function(){
    $(document.body).on('click', '[data-page="admin-tools"] #event-recalc-body button.cancel', function(event){
        event.preventDefault();

        $('[data-page="admin-tools"] #event-recalc-body').html('');
        $('[data-page="admin-tools"] #Recalc_event_id').val('');
    });

    $(document.body).on('click', '[data-page="admin-tools"] #event-simulator-body button.cancel', function(event){
        event.preventDefault();

        $('[data-page="admin-tools"] #event-simulator-body').html('');
        $('[data-page="admin-tools"] #Simulator_bet_id').val('');
    });


    $(document.body).on('click', '[data-page="admin-tools"] #event-simulator-body button.check', function(event){
        event.preventDefault();

        var $self = $(this);
        var data = {};
        $.each($self.closest('form').find('[data-event-block]'), function(i, el){
            var $block = $(el);
            var _data = $ajax.prepareFormData($block.find('.field').serializeArray());
            var event_id = _data['event_id'];
            var _result = {};
            $.each(_data, function(field, value){
                if(field != 'event_id') {
                    field = field.replace('Result[', '').replace(']', '');
                    _result[field] = value;
                }
            });

            data[event_id] = _result;
        });

        var bet_id = $self.closest('form').find('#bet_id').val();

        var callback = function(response){
            if(response.event_list !== undefined) {
                $.each(response.event_list, function(id, info){
                    var $block = $('[data-page="admin-tools"] #event-simulator-body [data-event-block="'+id+'"]');
                    $block.removeClass('border-shadow-red border-shadow-green');
                    if(info.result == 1)
                        $block.addClass('border-shadow-green');
                    else
                        $block.addClass('border-shadow-red');

                    var $log = $block.find('#log').html('');
                    $.each(info.explain, function(i, msg){
                        $log.append(msg);
                    });
                });
            }
        };
        $ajax.send($self.closest('form').attr('action'),{'List':data,'bet_id':bet_id},null,null,null,null,null,callback);
    });
});