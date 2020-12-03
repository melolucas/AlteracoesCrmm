<?php

class Help_articles_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        $this->table = 'help_articles';
        parent::__construct($this->table);
    }

    function get_details($options = array())
    {
        $help_categories_table = $this->db->dbprefix('help_categories');
        $help_articles_table = $this->db->dbprefix('help_articles');
              
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $help_articles_table.id=$id";
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
        /* if ($role == '1' || $role == '4' || $role == '24') {
            $where .= " AND ($help_articles_table.role_id = 1) OR ($help_articles_table.role_id = 2) OR ($help_articles_table.role_id = 4) OR ($help_articles_table.role_id = 24)";
        } else */if ($role ==  2) {
            $where .= " AND $help_articles_table.role_id = 2";    
        } elseif ($role == 3) {
            $where .= " AND ($help_articles_table.role_id = 2) OR ($help_articles_table.role_id = 3)";
        } elseif ($role == 5) {
            $where .= " AND ($help_articles_table.role_id = 2) OR ($help_articles_table.role_id = 5)";
        } elseif ($role == 6) {
            $where .= " AND ($help_articles_table.role_id = 2) OR ($help_articles_table.role_id = 6)";    
        } elseif ($role == 7) {
            $where .= " AND ($help_articles_table.role_id = 2) OR ($help_articles_table.role_id = 7)";  
        } elseif ($role == 8) {
            $where .= " AND ($help_articles_table.role_id = 2) OR ($help_articles_table.role_id = 8)";
        } elseif ($role == 9) {
            $where .= " AND ($help_articles_table.role_id = 2) OR ($help_articles_table.role_id = 9)";    
        } elseif ($role == 10) {
            $where .= " AND ($help_articles_table.role_id = 2) OR ($help_articles_table.role_id = 10)";    
        } elseif ($role == 11) {
            $where .= " AND ($help_articles_table.role_id = 2) OR ($help_articles_table.role_id = 11)";    
        } elseif ($role == 12) {
            $where .= " AND ($help_articles_table.role_id = 2) OR ($help_articles_table.role_id = 12)";        
        } elseif ($role == 13 || $role == 15 || $role == 16 || $role == 17 || $role == 18 || $role == 19 || $role == 20 || $role == 21 || $role == 22 || $role == 23) {
            $where .= " AND ($help_articles_table.role_id = 2) OR ($help_articles_table.role_id = 13) OR ($help_articles_table.role_id = 15) OR ($help_articles_table.role_id = 16) OR ($help_articles_table.role_id = 17) OR ($help_articles_table.role_id = 18) OR ($help_articles_table.role_id = 19) OR ($help_articles_table.role_id = 20) OR ($help_articles_table.role_id = 21) OR ($help_articles_table.role_id = 22) OR ($help_articles_table.role_id = 23)";
        } elseif ($role == 25) {
            $where .= "AND ($help_articles_table.role_id = 2) OR ($help_articles_table.role_id = 25)";
        }

        $sql = "SELECT $help_articles_table.*, $help_categories_table.title AS category_title, $help_categories_table.type, $help_articles_table.role_id 
        FROM $help_articles_table
        LEFT JOIN $help_categories_table ON $help_categories_table.id=$help_articles_table.category_id    
        WHERE $help_articles_table.deleted=0 AND $help_categories_table.deleted=0 $where";
        return $this->db->query($sql);
        //var_dump($sql); exit;
    }

    function get_articles_of_a_category($category_id)
    {
        $help_articles_table = $this->db->dbprefix('help_articles');

        $sql = "SELECT $help_articles_table.id, $help_articles_table.title
        FROM $help_articles_table     
        WHERE $help_articles_table.deleted=0 AND $help_articles_table.status='active' AND $help_articles_table.category_id=$category_id
        ORDER BY $help_articles_table.total_views DESC, $help_articles_table.title ASC";

        return $this->db->query($sql);
    }

    function increas_page_view($id)
    {
        $help_articles_table = $this->db->dbprefix('help_articles');

        $sql = "UPDATE $help_articles_table
        SET total_views = total_views+1 
        WHERE $help_articles_table.id=$id";

        return $this->db->query($sql);
    }

    function get_suggestions($type, $search)
    {
        $help_articles_table = $this->db->dbprefix('help_articles');
        $help_categories_table = $this->db->dbprefix('help_categories');

        $search = $this->db->escape_str($search);

        $sql = "SELECT $help_articles_table.id, $help_articles_table.title
        FROM $help_articles_table
        LEFT JOIN $help_categories_table ON $help_categories_table.id=$help_articles_table.category_id   
        WHERE $help_articles_table.deleted=0 AND $help_articles_table.status='active' AND $help_categories_table.deleted=0 AND $help_categories_table.status='active' AND $help_categories_table.type='$type'
            AND $help_articles_table.title LIKE '%$search%'
        ORDER BY $help_articles_table.title ASC
        LIMIT 0, 10";

        $result = $this->db->query($sql)->result();

        $result_array = array();
        foreach ($result as $value) {
            $result_array[] = array("value" => $value->id, "label" => $value->title);
        }

        return $result_array;
    }
}
