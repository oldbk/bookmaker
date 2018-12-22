if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.spoiler = function()
{
	return {
        getTemplate: function()
        {
            return String()
            + '<section id="redactor-modal-spoiler-insert">'
            + '<label>Название</label>'
            + '<input type="text" size="5" value="Спойлер" id="redactor-spoiler-text" />'
            + '<label>Текст под спойлером</label>'
            + '<textarea style="height: 150px;" id="redactor-spoiler-hidden"></textarea>'
            + '</section>';
        },
		init: function()
		{
            var button = this.button.add('spoiler', 'Spoiler');
            this.button.addCallback(button, this.spoiler.load);
		},
        load: function(buttonName)
        {
            this.modal.addTemplate('spoiler', this.spoiler.getTemplate());

            this.modal.load('spoiler', 'Спойлер', 500);
            this.modal.createCancelButton();

            var button = this.modal.createActionButton(this.lang.get('insert'));
            button.on('click', this.spoiler.insert);

            this.selection.save();
            this.modal.show();

            $('#redactor-table-rows').focus();
        },
        insert: function()
        {
            var text = $('#redactor-spoiler-text').val(),
                body = $('#redactor-spoiler-hidden').val();

            var data = '<div class="spoiler-block"><div class="spoiler-text btn2">'+text+'</div><div class="spoiler-hidden">'+body+'</div></div>';

            this.selection.restore();
            this.modal.close();

            var current = this.selection.getBlock() || this.selection.getCurrent();

            if (current) $(current).after(data);
            else
            {
                this.insert.html(data);
            }

            this.code.sync();
        }
	};
};