<?php
/* API Name: DeleteEvents
   Parameter: intUdId,deviceToken,deviceType,userid,eventid
   Description: API will delete event details
 */

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class DeleteEvents extends REST_Controller {

  function index(){

    $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
    if( !empty($alldevices) ){

                $userid = $this->input->post('userid');
                $eventid = $this->input->post('eventid');

                /**  Check with username and password */
                $checkUser = $this->model_name->select_data_by_condition('hoo_users', array('id' => $userid), '*', '', '', '', '', array());
                if( !empty($checkUser) ){

                        $eventDetails = $this->model_name->select_data_by_condition('hoo_events', array('id' => $eventid, 'is_delete' => '0'), '*', '', '', '', '', array());
                        if( !empty($eventDetails) ){

                          $update_array = array(
                              "is_delete" => '1',
                          );

                          if( $this->model_name->update_data($update_array, 'hoo_events', 'id', $eventid) ){
                              header('Content-Type: application/json');
                              $error = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Event details are deleted successfully');
                              echo json_encode($error);
                              exit;
      
                          }else{
                              header('Content-Type: application/json');
                              $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'Event details are not deleted successfully');
                              echo json_encode($error);
                              exit;

                          }

                        }else{
                          header('Content-Type: application/json');
                          $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'No such event exists');
                          echo json_encode($error);
                          exit;

                      }
                        
                        
                    
                }else{
                    header('Content-Type: application/json');
                    $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'UserId is incorrect');
                    echo json_encode($error);
                    exit;


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