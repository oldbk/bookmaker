/**
 * Created by me on 21.08.2015.
 */
$(function(){
    // create object
    var socket_private = new YiiNodeSocket();

    // add event listener
    socket_private.on('eventRemove2', function (data) {
        eventRemove2(data);
    });
});