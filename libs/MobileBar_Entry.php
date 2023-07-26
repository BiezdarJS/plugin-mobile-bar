<?php




    class MobileBar_Entry {
        
        private $id = NULL;
        private $slide_url = NULL;
        private $title = NULL;
        private $caption = NULL;
        private $read_more_url = NULL;
        private $position = NULL;
        private $published = 'yes';
        
        private $errors = array();
        
        private $exists = FALSE;
        
        
        function __construct($id = NULL) {
            $this->id = $id;
            $this->load();
        }
        private function load() {
            if(isset($this->id)) {
                $Model = new MobileBar_Model();
                $row = $Model->fetchRow($this->id);
                
                if(isset($row)) {
                    $this->setFields($row);
                    $this->exists = TRUE;
                }
            }
        }
        
        public function exists() {
            return $this->exists;
        }
        
        
        function getField($field) {
            if(isset($this->{$field})){
                return $this->{$field};
            }
               
               return NULL;
        }
        
        
        function isPublished() {
            return ($this->published =='yes');
        }
        
        
        function setFields($fields){
            foreach ($fields as $key => $val) {
                $this->{$key} = $val;
            }
        }
        
        function setError($field, $error) {
            $this->errors[$field] = $error;
        }
        
        function getError($field) {
            if(isset($this->errors[$field])) {
                return $this->errors[$field];
            }
            return NULL;
        }
        
        
        function hasId() {
            return isset($this->id);
        }
        
        function hasErrors() {
            return (count($this->errors) > 0);
        }
        
        
        
        
        function validate() {
            
        
            /*if(empty($this->img)) {
                
                $this->setError('img', 'To pole nie może być puste');
            } else 
                if (!filter_var($this->img, FILTER_VALIDATE_URL)) {
                    $this->setError('img', 'To pole musi być porawnym adresem URL');
            } else
                if (strlen($this_.img) > 255) {
                    $this->setError('img', 'To pole nie może być dłuże niż 255 znaków');
            }*/
            
            if(empty($this->img)) {
                
                $this->setError('img', 'To pole nie może być puste');
            } else
                if (strlen($this->img) > 255) {
                    $this->setError('img', 'To pole nie może być dłuże niż 255 znaków');
            }            
            
            
            if(empty($this->href)) {
                
                $this->setError('href', 'To pole nie może być puste');
            } else
                if (strlen($this->href) > 255) {
                    $this->setError('href', 'To pole nie może być dłuże niż 255 znaków');
            }            
            
            
            if(empty($this->title)) {
                
                $this->setError('title', 'To pole nie może być puste');
            } else
                if (strlen($this->title) > 255) {
                    $this->setError('title', 'To pole nie może być dłuże niż 255 znaków');
            }            
            
            if(empty($this->position)){
                $this->setError('position', 'To pole nie może być puste.');
            }else{
                $this->position = (int)$this->position;
                if($this->position < 1){
                    $this->setError('position', 'To pole musi być liczbą większą od 0.');
                }
            }            


            if(isset($this->published) && $this->published == 'yes'){
                $this->published = 'yes';
            }else{
                $this->published = 'no';
            }
            
            return (!$this->hasErrors());
            
            
        }
        
        
        
        
        
    }







?>