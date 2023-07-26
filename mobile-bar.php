<?php
    ob_start();
    session_start();

/*
* Plugin Name: Mobie Bar
* Plugin URI: http://piotrdeja.pl
* Description: Mobie Bar plugin
* Version: 1.0
* Author: Piotr Deja
* Author URI: http://piotrdeja.pl
* Licence: GPL2
*/

    require_once 'libs/MobileBar_Model.php';
    require_once 'libs/MobileBar_Entry.php';
    require_once 'libs/functions.php';
    require_once 'libs/Pagination.php';
    require_once 'libs/Request.php';


    class MobileBar {
        private static $plugin_id = 'mobile-bar';
        private $plugin_version = '1.0.0';
        
        private $user_capability = 'manage_options';
        
        private $model;
        
        private $action_token = 'wp-fn-action';
        
        private $pagination_limit = 3;
        
        function __construct() {
            $this->model = new MobileBar_Model();
            
            // uruchamianie podczas aktywacji
            register_activation_hook(__FILE__, array($this, 'onActivate'));
            
             //uruchamianie podczas deinstalacji
             register_uninstall_hook(__FILE__, array('MobileBar', 'onUninstall'));            
            
            // rejestracja przycisku w menu
            add_action('admin_menu', array($this, 'createAdminMenu'));
            
            //rejestracja skryptów panelu admina
            add_action('admin_enqueue_scripts', array($this, 'addAdminPageScripts'));
            
            //rejestracja akcji AJAX
            add_action('wp_ajax_checkValidPosition', array($this, 'checkValidPosition'));
             add_action('wp_ajax_getLastFreePosition', array($this, 'getLastFreePosition'));           
        }
        
        
        function addAdminPageScripts() {
            
            wp_register_script(
                'wp-fn-script', 
                plugins_url('/js/scripts.js', __FILE__), 
                array('jquery', 'media-upload', 'thickbox')
            );
          
            
            if(get_current_screen()->id == 'toplevel_page_'.static::$plugin_id) {
                wp_enqueue_script('jquery');
                wp_enqueue_script('thickbox');
                wp_enqueue_style('thickbox');                 
                wp_enqueue_script('media-upload');
                wp_enqueue_script('wp-fn-script');
            }
           
            
        }
        


        
     
        function checkValidPosition() {
            
            $position = isset($_POST['position']) ? (int)$_POST['position'] : 0;
            $message = '';
            
            if($position < 1) {
                $message = 'Podana wartość jest niepoprawnia. Pozycja musi być liczbą większą od 0.';
            
            } else if (!$this->model->isEmptyPosition($position)) {
                $message = 'Dana pozycja jest już zajęta';
            
            } else {
                $message = 'Ta pozycja jest wolna';
            }
            
            echo $message;
            die;
            
        }
        
        function getLastFreePosition() {
            echo $this->model->getLastFreePosition();
            die;
        }
        
        
         static function onUninstall(){
             $model = new MobileBar_Model();
             $model->dropTable();
             
             $ver_opt = static::$plugin_id.'-version';
             delete_option($ver_opt);
         }        
        
        
         function onActivate() {
             $ver_opt = static::$plugin_id.'-version';
             $installed_version = get_option($ver_opt);
             
                 $this->model->createDbTable();
                 update_option($ver_opt, $this->plugin_version);
         }
        
        
        
        function createAdminMenu() {
            
            add_menu_page(
                'Mobile Bar', 
                'Mobile Bar', 
                $this->user_capability, 
                static::$plugin_id, 
                array($this, 'printAdminPage')
            );
            add_submenu_page(
                'edu-menu-home', 
                'Navbar Opcje', 
                'navbar-opcje', 
                'manage_options', 
                static::$plugin_id, 
                array($this, 'printAdminPage')
            );          
        }
        
        function printAdminPage() {
            
            $request = Request::instance();
            
            $view = $request->getQuerySingleParam('view', 'index');
            $action = $request->getQuerySingleParam('action');
            $slideid = (int)$request->getQuerySingleParam('slideid');
            
            switch($view) {
                case 'index':
                    
                    
                     if($action == 'delete'){
                         
                         $token_name = $this->action_token.$slideid;
                         $wpnonce = $request->getQuerySingleParam('_wpnonce', NULL);
                         
                         if(wp_verify_nonce($wpnonce, $token_name)){
                             
                             if($this->model->deleteRow($slideid) !== FALSE){
                                 $this->setFlashMsg('Poprawnie usunięto slajd!');
                             }else{
                                 $this->setFlashMsg('Nie udało się usunąć slajdu', 'error');
                             }
                             
                         }else{
                             $this->setFlashMsg('Nie poprawny token akcji', 'error');
                         }
                         
                         $this->redirect($this->getAdminPageUrl());
                         
                     }else
                     if($action == 'bulk'){
                         
                         if($request->isMethod('POST') && check_admin_referer($this->action_token.'bulk')){
                             
                             $bulk_action = (isset($_POST['bulkaction'])) ? $_POST['bulkaction'] : NULL;
                             $bulk_check = (isset($_POST['bulkcheck'])) ? $_POST['bulkcheck'] : array();
                             
                             
                             if(count($bulk_check) < 1){
                                 $this->setFlashMsg('Brak slajdów do zmiany', 'error');
                             }else{
                                 
                                 if($bulk_action == 'delete'){
                                     
                                     if($this->model->bulkDelete($bulk_check) !== FALSE){
                                         $this->setFlashMsg('Poprawnie usunięto zaznaczone wpisy!');
                                     }else{
                                         $this->setFlashMsg('Nie udało się usunąć zaznaczonych wpisów', 'error');
                                     }
                                     
                                 }else
                                 if($bulk_action == 'public' || $bulk_action == 'private'){
                                     
                                     if($this->model->bulkChangePublic($bulk_check, $bulk_action) !== FALSE){
                                         $this->setFlashMsg('Poprawnie zmieniono status wpisów');
                                     }else{
                                         $this->setFlashMsg('Nie udało się zmienić statusu wpisów', 'error');
                                     }
                                     
                                 }
                                 
                             }
                             
                         }
                         
                         $this->redirect($this->getAdminPageUrl());
                     }
                    
                     $curr_page = (int)$request->getQuerySingleParam('paged', 1);
                     $order_by = $request->getQuerySingleParam('orderby', 'id');
                     $order_dir = $request->getQuerySingleParam('orderdir', 'asc');
                     
                     
                     $pagination = $this->model->getPagination($curr_page, $this->pagination_limit, $order_by, $order_dir);
                     
                     $this->render('index', array(
                         'Pagination' => $pagination
                     ));
                     break;
                    

                case 'form':
                    
                    if($slideid > 0) {
                        
                        $SlideEntry = new MobileBar_Entry($slideid);
                        
                        if(!$SlideEntry->exists()) {
                            $this->setFlashMsg('Brak takiego wpisu w bazie danych', 'error');
                            // Przekierowanie na index
                            $this->redirect($this->getAdminPageUrl());
                        }
                        
                    } else {
                        
                       $SlideEntry = new MobileBar_Entry(); 
                    }
                    
                    if ($action == 'save' && $request->isMethod('POST') && isset($_POST['entry'])) {
                        
                        if(check_admin_referer($this->action_token)) {
                        
                        $SlideEntry->setFields($_POST['entry']);
                        if($SlideEntry->validate()) {
                            
                            $entry_id = $this->model->saveEntry($SlideEntry);
                            
                            if($entry_id !== FALSE) {
                                
                                if($SlideEntry->hasId()) {
                                    $this->setFlashMsg('Poprawnie zmowydikowano wpis.');                                    
                                } else {
                                    $this->setFlashMsg('Poprawnie dodano nowy wpis.');                                    
                                }

                            } else {
                                $this->setFlashMsg('Wystąpiły błędy z zapisem do bazy danych.', 'error');
                            }
                            $this->redirect($this->getAdminPageUrl(array('view' => 'form', 'slideid' => $entry_id)));
                        } 
                        else {
                            $this->setFlashMsg('Popraw błędy formularza.', 'error');
                        }
                            
                        } else {
                            $this->setFlashMsg('Błędny token formularza!', 'error');
                        }
                        
                    }
                    
                    $this->render('form', array(
                        'Slide' => $SlideEntry
                    ));
                    break;
                default:
                    $this->render('404');
                    break;
                    
            }
            
            $this->render('index');
        }
        
        
        private function render($view, array $args = array()) {
            
            
            extract($args);
            
            
            $tmpl_dir = plugin_dir_path(__FILE__).'templates/';
            
            $view = $tmpl_dir.$view.'.php';
            
            require_once $tmpl_dir.'layout.php';
        }
        
        
        public function getAdminPageUrl(array $params = array()) {
            $admin_url = admin_url('admin.php?page='.static::$plugin_id);
            $admin_url = add_query_arg($params, $admin_url);
            
            return $admin_url;
        }
        
        
        public function setFlashMsg($message, $status = 'updated') {
            $_SESSION[__CLASS__]['message'] = $message;
            $_SESSION[__CLASS__]['status'] = $status;            
        }
        
        public function getFlashMsg() {
            if(isset($_SESSION[__CLASS__]['message'])) {
                $msg = $_SESSION[__CLASS__]['message'];
                unset($_SESSION[__CLASS__]);
                return $msg;
            }
            return NULL;
        }
        
        public function getFlashMsgStatus() {
            if(isset($_SESSION[__CLASS__]['status'])) {
                return $_SESSION[__CLASS__]['status'];
            }
            return NULL;            
        }
        
        public function hasFlashMsg() {
            return isset($_SESSION[__CLASS__]['message']);
        }
        
        
        public function redirect($location) {
            wp_safe_redirect($location);
            exit;
            
        }
        
        
    }

    $MobileBar = new MobileBar();



    ob_flush();

?>