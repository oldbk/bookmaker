/**
 * Created by me on 16.08.2015.
 */

var $eventList = new EventList();
$(function(){
    $eventList.run();
});

function EventList()
{
    var _that = this;
    this.event_list = {
        'scroll': []
    };

    this.run = function(){
        $(window).scroll(function(){
            $.each($eventList.event_list['scroll'], function(i, event){
                $(window).trigger(event);
            });
        });
    };

    this.add = function(event, trigger){
        _that.event_list[event].push(trigger);
    };

    this.remove = function(event, trigger){
        var array = _that.event_list[event];
        for(var i = array.length; i--;) {
            if(array[i] === trigger) {
                array.splice(i, 1);
            }
        }
    };
}