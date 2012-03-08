$( function() {

  var site_url = 'http://10.134.132.97:8080/';

	$('#post_tags').tagsInput({
		 //'autocomplete_url': url_to_autocomplete_api,
     //'autocomplete': { option: value, option: value},
     'interactive' : true,
     'defaultText' : 'Add some comma, separated, tags to your post',
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

     if($('#upload')){
          $('#createTribble').click( function(){
               $('#overlay').toggle();
               $('#overlay').height($('body').height()+210);               
          });
     }

     if($('#notifications')){

          $("#notifications a").smartupdater({
            url : site_url + 'notifications/get',
            dataType:'json',
            minTimeout: 1000 * 60 // 1 minute
            }, function (data) {

               var events = data[0];

               $("#notifications a").html(events.count);

               if(events.count > 0){
                 // $('#notifications').toggle();
                 $('#notifications').fadeIn('slow');
               }
              
              jQuery.each(events.notifications,function(key,value){
               $('#messages').append('<li id="'+value.event_id+'"><a href="/view/'+value.event_post_id+'" class="message">'+value.event_from_user_name+ ' ' + value.event_message +'</a></li>');
              });
            }
               // console.log(data.notifications);

          );

          $('#notifications a').click(function(e){
               $('#notifications ul#messages').toggle();
          });
     }

    if($('#post_image')){
      $("#post_image").fancybox({
      openEffect  : 'fade',
      closeEffect : 'fade',
      openSpeed: 'fast',
      closeSpeed: 'fast',
      closeBtn: true,
      aspectRatio: true,
      fixed: true,
      helpers : {
        title : {
          type : 'inside'
        }
      }
    });
    }
}); 