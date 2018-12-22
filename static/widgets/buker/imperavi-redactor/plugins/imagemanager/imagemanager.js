if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.imagemanager = function()
{
	return {
		init: function()
		{
			if (!this.opts.imageManagerJson) return;

			this.modal.addCallback('image', this.imagemanager.load);
		},
		load: function()
		{
			var $modal = this.modal.getModal();

			this.modal.createTabber($modal);
			//this.modal.addTab(1, 'Upload', 'active');
			//this.modal.addTab(1, 'Choose', 'active');

			$('#redactor-modal-image-droparea').addClass('redactor-tab redactor-tab1').hide();

			var $box = $('<div id="redactor-image-manager-box" style="overflow: auto; height: 300px;" class="redactor-tab redactor-tab2">').show();
			$modal.append($box);

			$.ajax({
			  dataType: "json",
			  cache: false,
			  url: this.opts.imageManagerJson,
			  success: $.proxy(function(data)
				{
					$.each(data.images, $.proxy(function(key, val)
					{
						if(val.breakpoint !== undefined) {
							$('#redactor-image-manager-box').append('<br>');
						} else {
							// title
							var thumbtitle = '';
							if (typeof val.title !== 'undefined') thumbtitle = val.title;

							var img = $('<img src="' + val.image + '" rel="' + val.image + '" title="' + thumbtitle + '" style="cursor: pointer;" />');
							$('#redactor-image-manager-box').append(img);
							$(img).click($.proxy(this.imagemanager.insert, this));	
						}
					}, this));


				}, this)
			});


		},
		insert: function(e)
		{
			this.image.insert('<img src="' + $(e.target).attr('rel') + '" alt="' + $(e.target).attr('title') + '">');
		}
	};
};