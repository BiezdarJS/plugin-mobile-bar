<?php 

    function mobile_bar_print_slides(){

        $model = new MobileBar_Model();

        $slides_list = $model->getPublishedSlides(); ?>

        
    <?php echo '<nav class="December2017_Mobile_Bar">' ?>        
        

    <?php     
        if(!empty($slides_list)){
        foreach($slides_list as $entry){
    ?>
    <?php echo '<a href="' ?><?php echo $entry->href; ?><?php echo '" class="December2017_Mobile_Bar__item">' ?>        
    <?php echo '<img src="' ?><?php echo plugins_url() . '/mobile-bar/img/' . $entry->img;?><?php echo '" alt="" />' ?>
    <?php echo '<span>' ?><?php echo $entry->title; ?><?php echo '</span>' ?>
    <?php echo '</a>' ?>    
    <?php } ?>


    <?php echo '</nav>' ?>        

     <?php        }
    }

?>





<?php


        /**
         * Registers a stylesheet.
         */
        function wpdocs_register_plugin_styles() {
            wp_register_style( 'mobilebar',  '/wp-content/plugins/mobile-bar/css/navbar.css' );
            wp_enqueue_style( 'mobilebar' );
        }
        // Register style sheet.
        add_action( 'wp_enqueue_scripts', 'wpdocs_register_plugin_styles' );        





    function getNumberOfItems() {


        $model2 = new MobileBar_Model();  
        
        $slides_list = $model2->getPublishedSlides();  
        
        $total_count = count($slides_list, COUNT_RECURSIVE);
        
        
        if ($total_count == 1) {
            
            echo "<style>
                .December2017_Mobile_Bar__item { width: 100%; }
                .December2017_Mobile_Bar__item:nth-of-type(1) { 
                border-right: 1px solid transparent; }
                </style>";
        } else
        if ($total_count == 2) {

                    echo "<style>
                        .December2017_Mobile_Bar__item { width: 50%; }
                .December2017_Mobile_Bar__item:nth-of-type(2) { 
                border-right: 1px solid transparent; }                        
                    </style>";
        } else
        if ($total_count == 3) {

                    echo "<style>
                        .December2017_Mobile_Bar__item { width: 33.333333% }
                .December2017_Mobile_Bar__item:nth-of-type(3) { 
                border-right: 1px solid transparent; }                        
                    </style>";
        } else 
        if ($total_count == 4) {

                    echo "<style>
                        .December2017_Mobile_Bar__item { width: 25% }
                .December2017_Mobile_Bar__item:nth-of-type(4) { 
                border-right: 1px solid transparent; }                        
                    </style>";
        }                
        
    }
    add_action('wp_head', 'getNumberOfItems');
        








?>