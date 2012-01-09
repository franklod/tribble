$(function() {
		$('#tags').tagsInput({
		 //'autocomplete_url': url_to_autocomplete_api,
     //'autocomplete': { option: value, option: value},
     'interactive':true,
     'defaultText':'Choose some tags for your work',
     //'onAddTag':callback_function,
     //'onRemoveTag':callback_function,
     //'onChange' : callback_function,
     'removeWithBackspace' : true,
     'minChars' : 0,
     'maxChars' : 0, //if not provided there is no limit
     'placeholderColor' : '#ccc'
	});
}); 