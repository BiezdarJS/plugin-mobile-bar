<?php



    class MobileBar_Model {
    private $table_name = 'mobile_bar';
    private $wpdb;
    
    
    function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }
    
    
    function getTableName(){
        return $this->wpdb->prefix.$this->table_name;
    }

    
    function createDbTable(){
        
        $table_name = $this->getTableName();
        
        $sql = '
            CREATE TABLE IF NOT EXISTS '.$table_name.'(
                id INT NOT NULL AUTO_INCREMENT,
                img VARCHAR(255) NOT NULL,
                href VARCHAR(255) NOT NULL,                
                title VARCHAR(255) NOT NULL,
                position INT NOT NULL,
                published enum("yes", "no") NOT NULL DEFAULT "yes",
                PRIMARY KEY(id)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8';
        
        require_once ABSPATH.'wp-admin/includes/upgrade.php';
        
        dbDelta($sql);
    }
    
        
    function isEmptyPosition($position) {
        $position = (int)$position;
        $table_name = $this->getTableName();
        
        $sql = "SELECT COUNT(*) FROM {$table_name} WHERE position = %d";
        $prep = $this->wpdb->prepare($sql, $position);
        
        $count = (int)$this->wpdb->get_var($prep);
        
        return ($count < 1);
    }
        
    function getLastFreePosition() {
        $table_name = $this->getTableName();
        $sql = "SELECT MAX(position) FROM {$table_name}";
        $pos = (int)$this->wpdb->get_var($sql);
        
        return ($pos+1);
    }        
        
        
    function saveEntry(MobileBar_Entry $entry) {
        $toSave = array(
            'img' => $entry->getField('img'),
            'href' => $entry->getField('href'),            
            'title' => $entry->getField('title'),
            'position' => $entry->getField('position'),         
            'published' => $entry->getField('published')           
        );
        
        $maps = array('%s', '%s','%s', '%d', '%s');
        
        $table_name = $this->getTableName();
        
        if($entry->hasId()) {
            if($this->wpdb->update($table_name, $toSave, array('id' => $entry->getField('id')), $maps, '%d')) {
                return $entry->getField('id');
            } else {
                return FALSE;
            }
        } else {
            
            if($this->wpdb->insert($table_name, $toSave, $maps)) {
                return $this->wpdb->insert_id;
            } else {
                return FALSE;
            }            
        }
        
        

    }
        
    function fetchRow($id) {
        $table_name = $this->getTableName();
        $sql = "SELECT * FROM {$table_name} WHERE id = %d";
        $prep = $this->wpdb->prepare($sql, $id);
        return $this->wpdb->get_row($prep);
    }
        
        
        
    function getPagination($curr_page, $limit = 10, $order_by = 'id', $order_dir = 'asc') {
        $curr_page = (int)$curr_page;
        if($curr_page < 1 ) {
            $curr_page = 1;
        }
        $limit = (int)$limit;
        
        $order_by_opts = static::getOrderByOpts();
        $order_by = (!in_array($order_by, $order_by_opts)) ? 'id' : $order_by;
        
        
        $order_dir = in_array($order_dir, array('asc', 'desc')) ? $order_dir : 'asc';
        
        $offset = ($curr_page-1)*$limit;
        
        $table_name = $this->getTableName();
        
        $count_sql = "SELECT COUNT(*) FROM {$table_name}";
        $total_count = $this->wpdb->get_var($count_sql);
        
        $last_page = ceil($total_count/$limit);
        
        $sql = "SELECT * FROM {$table_name} ORDER BY {$order_by} {$order_dir}, {$limit} LIMIT {$offset}, {$limit}"; 
        
        $slides_list = $this->wpdb->get_results($sql);
        
        
        $Pagination = new Pagination($slides_list, $order_by, $order_dir, $limit, $total_count, $curr_page, $last_page);
        
        return $Pagination;
        
    }
        
    static function getOrderByOpts() {
        return array(
            'ID' => 'id',
            'Pozycja' => 'position',
            'Widoczność' => 'published'
        );
    }
        
        
    function deleteRow($id) {
        $id = (int)$id;
        
        $table_name = $this->getTableName();
        $sql = "DELETE FROM {$table_name} WHERE id = %d";
        $prep = $this->wpdb->prepare($sql, $id);
        
        return $this->wpdb->query($prep);
    }
        
    function bulkDelete(array $ids_list){
        $ids_list = array_map('intval', $ids_list);
        
        $table_name = $this->getTableName();
        
        $ids_str = implode(',', $ids_list);
        $sql = "DELETE FROM {$table_name} WHERE id IN ({$ids_str})";
        return $this->wpdb->query($sql);
    }
    
    function bulkChangePublic(array $ids_list, $change_to){
        $ids_list = array_map('intval', $ids_list);
        
        $status = '';
        switch($change_to){
            default:
            case 'public': $status = 'yes'; break;
            case 'private': $status = 'no'; break;
        }
        
        $table_name = $this->getTableName();
        $ids_str = implode(',', $ids_list);
        
        $sql = "UPDATE {$table_name} SET published = '{$status}' WHERE id IN ({$ids_str})";
        return $this->wpdb->query($sql);
    }
        
    function getPublishedSlides(){
        $table_name = $this->getTableName();
        
        $sql = "SELECT * FROM {$table_name} WHERE published = 'yes' ORDER BY position";
        return $this->wpdb->get_results($sql);
    }
    
    function dropTable(){
        $table_name = $this->getTableName();
        $sql = "DROP TABLE {$table_name}";
        return $this->wpdb->query($sql);
    }        
        
        
        
        
    }




?>