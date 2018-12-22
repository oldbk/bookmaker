/**
 * Created by me on 15.04.2015.
 */
$(function(){
    $(document.body).on('touchstart click', '#TeamAliasNew_is_main', function(){
        var $self = $(this);
        if($self.is(':checked')) {
            $('[for="TeamAliasNew_parent"]').closest('.form-group').hide();
        } else {
            $('[for="TeamAliasNew_parent"]').closest('.form-group').show();
        }
    });
});

function alias(action, id)
{
    switch (action) {
        case 'delete':
            $('[data-alias-new="'+id+'"]').remove();
            break;
    }
}
