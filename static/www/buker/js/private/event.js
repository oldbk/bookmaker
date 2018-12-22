/**
 * Created by me on 21.08.2015.
 */
/**
 * Remove from trash
 * @param data
 */
function eventRemove2(data)
{
    console.log('Remove2', data);

    if(data['event_ids'] === undefined)
        return;

    $.each(data['event_ids'], function(i, id){
        $('table[data-type="trash"] tr[data-event="'+id+'"]').remove();
    });
}