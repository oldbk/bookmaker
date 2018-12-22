/**
 * Created by me on 03.08.2015.
 */
var socket;
$(function(){
    // create object
    try {
        socket = new YiiNodeSocket();

        // enable debug mode
        socket.debug(_d);

        socket.onConnect(function () {
            //console.log('onConnect');
        });

        socket.onDisconnect(function () {
            //console.log('onDisconnect');
        });

        socket.onConnecting(function () {
            //console.log('onConnecting');
        });

        socket.onReconnect(function () {
            //console.log('onReconnect');
        });

        // add event listener
        socket.on('eventChange', function (data) {
            eventChange(data);
        });

        // add event listener
        socket.on('eventRemove', function (data) {
            eventRemove(data);
        });

        socket.on('exCommand', function (data) {
            if(data.name !== undefined) {
                switch (data.name) {
                    case 'reload':
                        window.location.reload();
                        break;
                    case 'link':
                        window.location.href = data.link;
                        break;
                    default:
                        DEBUG(_d, 'info', data.name, data.params);
                        $ajax.runJS({ name: data.name, 'params': data.params });
                        break;
                }
            }
        });
    } catch (ex) {
        if(_d) {
            DEBUG(_d, 'error', 'Socket', ex);
        }
    }
});