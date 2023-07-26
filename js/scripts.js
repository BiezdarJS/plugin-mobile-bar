


    (function($){
        
        
        $(document).ready(function() {
            
            $('#wp-fn-position').keyup(function() {
                
                var $this = $(this); 

                $('#pos-info').text('Trwa sprawdzanie pozycji...');
                
                var post_data = {
                    position: $this.val(),
                    action: 'checkValidPosition'
                };
                
                $.post(ajaxurl, post_data, function(result) {
                    $('#pos-info').text(result);
                });
                
               
            });
            
            $('#get-last-pos').click(function() {
                
               $('#pos-info').text('Trwa pobieranie pozycji...');
                
                var get_data = {
                    
                    action: 'getLastFreePosition'
                }
                
                $.get(ajaxurl, get_data, function(result){
                    
                    $('#wp-fn-position').val(result);
                    $('#pos-info').text('Pozycja została pobrana');                    
                    
                });
                
            });
            
            
            
            window.send_to_editor = function(html) {
                
                var img_url = $(html).attr('src');
                
                jQuery('#wp-fn-img').val(img_url);
                tb_remove();
                
                var $prevImg = jQuery('<img>').attr('src', img_url);
                console.log($prevImg);
                $('#img-preview').empty().append($prevImg);
            };
            
            
            $('#select-img-btn').click(function(){
               var url = 'media-upload.php?TB_iframe=true&type=image'; 
                tb_show('Wybierz slajd', url, false);
                return false;
            }); 
        });
        
        
        
    })(jQuery);