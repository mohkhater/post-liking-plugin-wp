
(function ($) { 

 
    // like button
    $('#btn_like').on('click', function (e) {
        e.preventDefault();
        var data = {
                action: 'post-liking',
                nonce: $('#nonce').val(),
                post_id: $('#post_id').val(),
                btn_liking: $('#btn_like').val(),
                btn:'like'
            };
    
      //  var data  = $('#liking-form').serialize();
        var t =$('#btn_like');


      // send data by url and recieve response
        $.post(data_ajax.ajax_url, data, function (response) {
            $('#like-count').html(`${response.post_like}`);
                    if( t.val() =="true"){
                        t.val("false");
                        t.addClass('green').removeClass('gray');
                       
                    }
                    else if( t.val()=="false"){
                        t.val("true");
                         t.addClass('gray').removeClass('green');
                
                    }
                
          

        })
    })

    // dislike button
      $('#btn_dislike').on('click', function (e) {
        e.preventDefault();
        var data = {
                action: 'post-liking',
                nonce: $('#nonce').val(),
                post_id: $('#post_id').val(),
                btn_liking: $('#btn_dislike').val(),
                btn:'dislike'
            };
          var d = $('#btn_dislike');
          
      // send data by url and recieve response
        $.post(data_ajax.ajax_url, data, function (response) {
      
            $('#dislike-count').html(`${response.post_dislike}`);

            if (d.val() == "true") {
                        
                        d.val("false");
                        d.addClass('red').removeClass('gray');
                       
                    }
                    else if( d.val()=="false"){
                        d.val("true");
                         d.addClass('gray').removeClass('red');
                
                    }
                
          

        })
      })
    
})(jQuery);



