<?php
/* API Name: DeleteEvents
   Parameter: intUdId,deviceToken,deviceType,userid,name,eventdate
   Description: API will search event details
 */

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class SearchEvents extends REST_Controller {

  function index(){

    $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
    if( !empty($alldevices) ){

                $eventname = trim($this->input->post('eventname'));
                $eventdate = date('Y-m-d h:i:s',strtotime($this->input->post('eventdate')));

                
                if( $eventname != "" ){ 
                  /** Search events based on name  */
                  $searchByNameDetails = $this->model_name->select_data_by_condition('hoo_events', array('event_name = "'. $eventname .'"', 'is_delete' => '0'), '*', '', '', '', '', array());
                  if( !empty($searchByNameDetails) ){
                        header('Content-Type: application/json');
                        $error = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Event Details', 'data' => $searchByNameDetails);
                        echo json_encode($error);
                        exit;

                  }else{
                        header('Content-Type: application/json');
                        $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'No such event exists with this name');
                        echo json_encode($error);
                        exit;

                  }

                }

                if( $eventdate != "" ){

                  /** Search events based on date and time  */

                  $searchByDateDetails = $this->model_name->select_data_by_condition('hoo_events',array('event_date <=' => $eventdate , 'is_delete' => '0'), '*', '', '', '', '', array());
                  if( !empty($searchByDateDetails) ){
                      header('Content-Type: application/json');
                      $error = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Event Details', 'data' => $searchByDateDetails);
                      echo json_encode($error);
                      exit;
  
                  }else{
                      header('Content-Type: application/json');
                      $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'No such event is going to occur on this date');
                      echo json_encode($error);
                      exit;
  
                  }

              }
          }else{
            header('Content-Type: application/json');
            $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
            echo json_encode($error);
            exit;
        }
    }
}

?>