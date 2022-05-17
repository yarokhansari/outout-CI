<?php
/*
API Name: Upcoming Events
Parameter: intUdId,deviceToken,deviceType,userid
Description: API will list out all event details
*/

require 'BaseApi.php';

class UpcomingEvents extends BaseApi {
    function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
            $userid = $this->input->post('userid');
            $limit = $this->input->post('limit');
            $offset = $this->input->post('offset');
			$today = date('Y-m-d');

            $this->db->select("*");
			$this->db->from('hoo_events');
			
			$this->db->where("is_delete = '0'");
			$this->db->where("DATE_FORMAT(event_date,'%Y-%m-%d') >= '$today'");
			$this->db->where("userid = $userid");
			$this->db->order_by('event_date','DESC');
			$this->db->limit($limit, $offset);
		
			$events = $this->db->get()->result_array();
			
			foreach ($events as $key => $value) {
				$event_invitees = $value["event_invitees"];
				$this->db->select("concat(first_name,' ',last_name) as full_name");
				$this->db->from('hoo_users');
				$this->db->where("id IN ($event_invitees)");
				$this->db->where("is_delete = '0'");
				$this->db->where("is_verified = '1'");
				$user_names = $this->db->get()->result_array();
				
				$names = "";
				
				
				foreach ($user_names as $name) {
					$names .= $name["full_name"].",";
				}
				$events[$key]["event_invitees_name"] = rtrim($names, ", ");
			}
			
            if(!empty($events)) {
                $response = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Upcoming Event Details', 'data' => $events);
            }
            else {
                $response = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'No such event found.');
            }
        }
        else {
            $response = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

?>