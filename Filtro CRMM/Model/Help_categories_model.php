<?php

class Help_categories_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'help_categories';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $help_categories_table = $this->db->dbprefix('help_categories');
        $help_articles_table = $this->db->dbprefix('help_articles');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $help_categories_table.id=$id";
        }

        $type = get_array_value($options, "type");
        if ($type) {
            $where .= " AND $help_categories_table.type='$type'";
        }

        
        $only_active_categories = get_array_value($options, "only_active_categories");
        if ($only_active_categories) {
            $where .= " AND $help_categories_table.status='active'";
        }
        
        $role = $this->login_user->role_id;
        if ($role == '1' || $role == '4' || $role == '24') {
            $where .= " AND ($help_categories_table.role_id = 1) OR ($help_categories_table.role_id = 2) OR ($help_categories_table.role_id = 4) OR ($help_categories_table.role_id = 24)";
        } elseif ($role ==  2) {
            $where .= " AND $help_categories_table.role_id = 2";    
        } elseif ($role == 3) {
            $where .= " AND ($help_categories_table.role_id = 2) OR ($help_categories_table.role_id = 3)";
        } elseif ($role == 5) {
            $where .= " AND ($help_categories_table.role_id = 2) OR ($help_categories_table.role_id = 5)";
        } elseif ($role == 6) {
            $where .= " AND ($help_categories_table.role_id = 2) OR ($help_categories_table.role_id = 6)";    
        } elseif ($role == 7) {
            $where .= " AND ($help_categories_table.role_id = 2) OR ($help_categories_table.role_id = 7)";  
        } elseif ($role == 8) {
            $where .= " AND ($help_categories_table.role_id = 2) OR ($help_categories_table.role_id = 8)";
        } elseif ($role == 9) {
            $where .= " AND ($help_categories_table.role_id = 2) OR ($help_categories_table.role_id = 9)";    
        } elseif ($role == 10) {
            $where .= " AND ($help_categories_table.role_id = 2) OR ($help_categories_table.role_id = 10)";    
        } elseif ($role == 11) {
            $where .= " AND ($help_categories_table.role_id = 2) OR ($help_categories_table.role_id = 11)";    
        } elseif ($role == 12) {
            $where .= " AND ($help_categories_table.role_id = 2) OR ($help_categories_table.role_id = 12)";        
        } elseif ($role == 13 || $role == 15 || $role == 16 || $role == 17 || $role == 18 || $role == 19 || $role == 20 || $role == 21 || $role == 22 || $role == 23) {
            $where .= " AND ($help_categories_table.role_id = 2) OR ($help_categories_table.role_id = 13) OR ($help_categories_table.role_id = 15) OR ($help_categories_table.role_id = 16) OR ($help_categories_table.role_id = 17) OR ($help_categories_table.role_id = 18) OR ($help_categories_table.role_id = 19) OR ($help_categories_table.role_id = 20) OR ($help_categories_table.role_id = 21) OR ($help_categories_table.role_id = 22) OR ($help_categories_table.role_id = 23)";
        } elseif ($role == 25) {
            $where .= "AND ($help_categories_table.role_id = 2) OR ($help_categories_table.role_id = 25)";
        }
      
        $sql = "SELECT $help_categories_table.*, 
                (SELECT COUNT($help_articles_table.id) AS total_articles FROM $help_articles_table WHERE $help_articles_table.category_id=$help_categories_table.id AND $help_articles_table.deleted=0 AND  $help_articles_table.status='active') AS total_articles
        FROM $help_categories_table
        WHERE $help_categories_table.deleted=0 $where 
        ORDER BY $help_categories_table.sort";
        return $this->db->query($sql);
        }

}