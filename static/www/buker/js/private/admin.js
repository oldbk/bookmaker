/**
 * Created by me on 25.05.2015.
 */

$(function() {
    $(window).on('page:loaded', function(){
        var $temp = $('.auto-source-user-list');

        if(_d) {
            console.log('USER', $temp);
        }

        $temp.off('touchstart click');
        $temp.on('touchstart click', function(){
            var $self = $(this);

            $self.autocomplete({
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
                    $('[data-source-hidden="'+$self.data('source-hidden-for')+'"]').val(ui.item['id']);
                }
            });
        });

        $temp.off('blur');
        $temp.on('blur', function(){
            var $self = $(this);
            if($.trim($self.val()).length == 0)
                $('[data-source-hidden="'+$self.data('source-hidden-for')+'"]').val('');
        });

        $temp = $('.problem-no-result #Result_is_cancel');
        $temp.off('touchstart click');
        $temp.on('touchstart click', function(){
            var disabled = false;
            if($(this).is(':checked')) {
                disabled = true;
            }

            $(this).closest('.problem-no-result').find('input[type="text"]').val('').prop('disabled', disabled);
        });

        var $problem = $('.calc-1');
        $problem.off('change');
        $problem.on('change', function(){
            var team = 1;
            if($(this).hasClass('result-team2'))
                team = 2;

            var $block = $(this).closest('.problem-no-result');
            var sum = 0;
            $.each($block.find('.result-team'+team), function(i, el){
                var value = parseInt($(el).val());
                if(!isNaN(value))
                    sum += value;
            });

            $block.find('#Result_team_'+team+'_result').val(sum);
        });

        var $problem2 = $('.calc-2');
        $problem2.off('change');
        $problem2.on('change', function(){
            var sum1 = 0;
            var sum2 = 0;
            var $block = $(this).closest('.problem-no-result');

            $.each($block.find('input[data-team="1"]'), function(i, el){
                var $team1 = $(el);
                var $team2 = $block.find('[name="Result[team_2_part_'+$(el).data('part')+']"]');
                var value = parseInt($team1.val());
                var value2 = parseInt($team2.val());
                if(isNaN(value) || isNaN(value2))
                    return;

                if(value > value2)
                    sum1 += 1;
                else if(value < value2)
                    sum2 += 1;
            });

            console.log(sum1, sum2);
            $block.find('#Result_team_1_result').val(sum1);
            $block.find('#Result_team_2_result').val(sum2);
        });
    });
});

function updateProblemCount(count)
{
    var $el = $('.user-menu li:contains("Проблемы")');
    if(count == 0)
        $el.html('Проблемы');
    else
        $el.html('Проблемы (<span style="color: red;">'+count+'</span>)');

}