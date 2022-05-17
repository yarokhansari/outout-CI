<?php

class Custom extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('email');
    }

    public function getUsers($user_id = 0, $account_type = "", $is_vip = "", $search_query = "", $offset = 0, $limit = 0) {
        $append_query = "
        SELECT (CASE WHEN from_user_id = '{$user_id}' THEN to_user_id ELSE from_user_id END) AS user_id,
        from_user_id, to_user_id, is_follow, status
        FROM `hoo_friend_request` WHERE from_user_id = '{$user_id}'
        AND status <> '2' AND is_delete = '0'
        ";

        $this->db->select("t4.id as user_id, concat(t4.first_name,' ',t4.last_name) as full_name, t4.dob, t4.gender,");
        $this->db->select("t4.phone_number, t4.email, t4.username, t4.city, t4.profile_image, t4.account_type,");
        $this->db->select("t4.is_vip, t5.is_follow, (case when t5.status = '2' then '' else t5.status end) as status,");
        $this->db->select("IFNULL(t5.from_user_id, '') AS from_user_id, IFNULL(t5.to_user_id, '') AS to_user_id");
        $this->db->from('hoo_users as t4');
        $this->db->join('('.$append_query.') as t5', 't4.id = t5.user_id', 'LEFT');
        $this->db->where("t4.is_delete = '0'");

        if ($account_type <> "")
            $this->db->where("t4.account_type = '{$account_type}'");

        if ($is_vip <> "")
            $this->db->where("t4.is_vip = '{$is_vip}'");

        if ($search_query <> "") {
            /*$array = array('first_name' => $search_query, 'last_name' => $search_query, 'email' => $search_query);
            $this->db->or_like($array);*/
            /*$this->db->like("first_name", $search_query);
            $this->db->or_like("last_name", $search_query);
            $this->db->or_like("email", $search_query);*/
            $this->db->where("(t4.first_name LIKE '%{$search_query}%' OR t4.last_name LIKE '%{$search_query}%' OR t4.email LIKE '%{$search_query}%')");
        }

        //$this->db->order_by('fr.final_report_id', 'DESC');
        if ($limit > 0)
            $this->db->limit($limit, $offset);

        return $this->db->get()->result_array();
    }

    public function getBusinessUsers($search_query = "", $offset = 0, $limit = 0) {
        $this->db->select("*");
        $this->db->from('hoo_users');
        $this->db->where("is_delete = '0'");
        $this->db->where("account_type = '1'");

        if ($search_query <> "") {
            $this->db->where("(first_name LIKE '%{$search_query}%' OR last_name LIKE '%{$search_query}%' OR email LIKE '%{$search_query}%')");
        }

        if ($limit > 0)
            $this->db->limit($limit, $offset);

        return $this->db->get()->result_array();
    }

    public function getFriendRequestRecord($from_user_id, $to_user_id) {
        $this->db->select("*");
        $this->db->from('hoo_friend_request');
        $this->db->where("is_delete = '0'");
        $this->db->where("status <> '2'");
        $this->db->where("from_user_id = '{$from_user_id}'");
        $this->db->where("to_user_id = '{$to_user_id}'");
        $this->db->order_by('id', 'DESC');
        return $this->db->get()->row_array();
    }

    public function getNotifications($user_id, $account_type = "", $is_vip = "") {
        $this->db->select("t5.*, concat(t6.first_name, ' ', t6.last_name) as full_name, t6.city");
        $this->db->from('hoo_notifications as t4');
        $this->db->join('hoo_memory_media as t5', 't4.media_id = t5.id');
        $this->db->join('hoo_users as t6', 't5.user_id = t6.id');
        $this->db->where("t4.user_id = '{$user_id}'");

        if ($account_type <> "")
            $this->db->where("t6.account_type = '{$account_type}'");

        if ($is_vip <> "")
            $this->db->where("t6.is_vip = '{$is_vip}'");

        return $this->db->get()->result_array();
    }

    public function getEvents($userid = "", $event_type = "", $search_query = "", $offset = 0, $limit = 0) {
        $this->db->select("*");
        $this->db->from('hoo_events');
        $this->db->where("is_delete = '0'");
		$this->db->order_by('event_date','DESC');
	
        if ($userid > 0)
            $this->db->where("userid = '{$userid}'");

        if ($event_type <> "")
            $this->db->where("event_type = '{$event_type}'");

        if ($search_query <> "") {
            $this->db->where("(event_name LIKE '%{$search_query}%' OR event_city LIKE '%{$search_query}%')");
        }

        if ($limit > 0)
            $this->db->limit($limit, $offset);

        $events = $this->db->get()->result_array();

        foreach ($events as $key => $value) {
            $event_invitees = $value["event_invitees"];
            $this->db->select("concat(first_name,' ',last_name) as full_name");
            $this->db->from('hoo_users');
            $this->db->where("id IN ('{$event_invitees}')");
            $user_names = $this->db->get()->result_array();
            $names = "";
            foreach ($user_names as $name) {
                $names = $name["full_name"].",";
            }
            $events[$key]["event_invitees_name"] = rtrim($names, ", ");
        }
        return $events;
    }

    public function getMyFriends($user_id, $search_query = "", $offset = 0, $limit = 0) {
        $this->db->select("to_user_id as user_id, is_follow");
        $this->db->from('hoo_friend_request');
        $this->db->where("is_delete = '0'");
        $this->db->where("status = '1'");
        $this->db->where("from_user_id = '{$user_id}'");
        $this->db->order_by('id', 'DESC');
        $friends1 = $this->db->get()->result_array();

        $this->db->select("from_user_id as user_id, is_follow");
        $this->db->from('hoo_friend_request');
        $this->db->where("is_delete = '0'");
        $this->db->where("status = '1'");
        $this->db->where("to_user_id = '{$user_id}'");
        $this->db->order_by('id', 'DESC');
        $friends2 = $this->db->get()->result_array();

        $friends = array_merge($friends1, $friends2);
		
        $friends_array = array();
		if( !empty($friends )){
			 foreach ($friends as $key => $value) {
				array_push($friends_array, $value["user_id"]);
			 }
		}else{
			$friends_array = array();
		}
       

        $append_query = "
        SELECT is_follow FROM hoo_friend_request 
        WHERE is_delete = '0' AND status = '1' AND ((from_user_id = '{$user_id}' AND to_user_id = t4.id))";

        $this->db->select("t4.id as user_id, concat(t4.first_name,' ',t4.last_name) as full_name, t4.dob, t4.gender,");
        $this->db->select("t4.phone_number, t4.email, t4.username, t4.city, t4.profile_image, t4.account_type,");
        $this->db->select("t4.is_vip, (".$append_query.") AS is_follow");
        $this->db->from('hoo_users as t4');
		
		if( !empty($friends_array )){
			 $this->db->where("t4.id IN (".implode(',', $friends_array).") AND t4.is_verified = '1'");
		}
       

        if ($search_query <> "") {
            $this->db->where("(t4.first_name LIKE '%{$search_query}%' OR t4.last_name LIKE '%{$search_query}%' OR t4.email LIKE '%{$search_query}%') AND t4.is_verified = '1'");
        }

        if ($limit > 0)
            $this->db->limit($limit, $offset);
		
        return $this->db->get()->result_array();
    }

    public function getNearbyUsers($latitude, $longitude, $radius, $account_type = "", $search_query = "", $offset = 0, $limit = 0) {
        $km = 6371;

        if ($latitude <> "" && $latitude > 0 ) {
            $this->db->select("*, ({$km} * acos(cos(radians({$latitude})) * cos(radians(latitude)) * cos(radians(longitude) - radians({$longitude})) + sin(radians({$latitude})) * sin(radians(latitude)))) AS distance");
            $this->db->from("hoo_users as t4");
        }
        else {
            $this->db->select("*, 0 AS distance");
            $this->db->from("hoo_users as t4");
        }
        if ($account_type <> "")
            $this->db->where("t4.account_type = '{$account_type}' having distance < 28");

        if ($search_query <> "") {
            $this->db->where("(t4.first_name LIKE '%{$search_query}%' OR t4.last_name LIKE '%{$search_query}%' OR t4.email LIKE '%{$search_query}%')");
        }

  
        //$this->db->where("having distance < 28");
        $this->db->order_by("distance");
        if ($limit > 0)
            $this->db->limit($limit, $offset);

        return $this->db->get()->result_array();

    }
    function getcheckindata($tablename, $search_query = "", $data = '*', $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str = array()) {

        $this->db->select($data);
        $this->db->from($tablename);

        //if join_str array is not empty then implement the join query
        if (!empty($join_str)) {
            foreach ($join_str as $join) {
                if (!isset($join['join_type'])) {
                    $this->db->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id']);
                } else {
                    $this->db->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id'], $join['join_type']);
                }
            }
        }

        //condition array pass to where condition
        $this->db->where($search_query);


        //Setting Limit for Paging
        if ($limit != '' && $offset == 0) {
            $this->db->limit($limit);
        } else if ($limit != '' && $offset != 0) {
            $this->db->limit($limit, $offset);
        }

        //order by query
        if ($sortby != '' && $orderby != '') {
            $this->db->order_by($sortby, $orderby);
        }

        $query = $this->db->get();

        //if limit is empty then returns total count
        if ($limit == '') {
            $query->num_rows();
        }

        //if limit is not empty then return result array
        return $query->result_array();
    }


    
    
}
