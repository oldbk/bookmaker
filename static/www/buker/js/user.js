/**
 * Created by me on 15.04.2015.
 */
$(function(){
    $(document.body).on('touchstart click', '#user-search-auto', function(){
        var $self = $(this);

        $('#user-search-auto').autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: $self.data('source'),
                    dataType: "json",
                    data: {
                        q: request.term
                    },
                    success: function( data ) {
                        response( data.items );
                    }
                });
            },
            minLength: 3,
            select: function(event, ui) {
                $ajax.send($self.data('link'), {'user': ui.item['id']});
            }
        });
    });
});

function closeUserEdit()
{
    $("#replace-info-block .panel").remove();
}
