 $(document).ready(function(){
     
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

     // $('form').submit(function() {
       
     //   var docHeight = $(document).height();

     //    $("body").append("<div id='overlay'></div>");

     //    $("#overlay")
     //       .height(docHeight)
     //       .css({
     //          'opacity' : 0.8,
     //          'position': 'absolute',
     //          'top': 0,
     //          'left': 0,
     //          'background-color': 'black',
     //          'width': '100%',
     //          'z-index': 5000
     //       });

     //   return false;
     // });

 });