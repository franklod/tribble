$(function() {
		$('#post_tags').tagsInput({
		 //'autocomplete_url': url_to_autocomplete_api,
     //'autocomplete': { option: value, option: value},
     'interactive':true,
     'defaultText':'Add some comma, separated, tags to your post',
     //'onAddTag':callback_function,
     //'onRemoveTag':callback_function,
     //'onChange' : callback_function,
     'removeWithBackspace' : true,
     'minChars' : 3,
     'maxChars' : 0, //if not provided there is no limit
     'placeholderColor' : '#ccc',
     // width:'300px',
     //  height:'29px'
	});
}); 