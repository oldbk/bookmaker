/**
 * Created by me on 04.08.2015.
 */

function eventChange(data)
{
    if(data['items'] === undefined)
        return;

    $.each(data['items'], function(id, events){
        var $eventList = $('[data-event="'+id+'"]');
        $.each($eventList, function(i, el){
            var $event = $(el);

            $.each(events[$user.getBalanceType()], function(type, value){
                var $prop = $event.find('td .ratio span[data-type="'+type+'"]');
                var $ratioBlock = $prop.closest('.ratio');
                var $propUpDown = $ratioBlock.find('span.glyphicon');

                if($propUpDown.length == 0) {
                    $propUpDown = $('<span>', {'class': 'glyphicon', 'data-toggle':'tooltip'});
                    $ratioBlock.append($propUpDown)
                }

                $propUpDown.removeClass('glyphicon-arrow-up glyphicon-arrow-down ratio-up ratio-down green red');

                if(value != '') {
                    var val = parseFloat($prop.text());
                    var diff;

                    if(val < value) {
                        diff = Math.floor( parseFloat((100 * (value - val)).toFixed())) / 100;
                        $propUpDown
                            .addClass('glyphicon-arrow-up ratio-up green')
                            .attr('data-original-title', '+'+diff)
                            .attr('title', '+'+diff);
                        $ratioBlock
                            .addClass('ratio-green')
                            .removeClass('ratio-green', 2000)
                    } else if(val > value) {
                        diff = Math.floor( parseFloat((100 * (val - value)).toFixed())) / 100;
                        $propUpDown
                            .addClass('glyphicon-arrow-down ratio-down red')
                            .attr('data-original-title', '-'+diff)
                            .attr('title', '-'+diff);

                        $ratioBlock
                            .addClass('ratio-red')
                            .removeClass('ratio-red', 2000);
                    } else
                        $propUpDown.remove();
                } else
                    $propUpDown.remove();

                $prop
                    .attr('data-value', value)
                    .text(value);
            });
        });
    });
}

function eventRemove(data)
{
    console.log('Remove', data);

    if(data['event_ids'] === undefined)
        return;

    $.each(data['event_ids'], function(i, id){
        $('table[data-type="event-line"] tr[data-event="'+id+'"]').remove();

        //@TODO delete from coupon
    });
}

