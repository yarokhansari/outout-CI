<?php

defined('BASEPATH') or exit('No direct script access allowed');

abstract class REST_Controller extends CI_Controller {

    public function __construct() {

        $this->flag = FALSE;

        parent::__construct();

        $this->load->library('encryption');
		

        //$this->load->library('AES');			

        $this->load->model('Common', 'model_name');
        $this->load->model('Custom', 'custom'); //Added By: Yogen Jajal

        //$api_key = $this->model_name->getSettingData("API_KEY");

        $api_key = "w3e2rosrt3y5u6iter8iug4h58mf1e0";

        if (isset($_REQUEST['apiKey'])) {

            $param = $_REQUEST;

            $apiKey = $param['apiKey'];



            if (!empty($apiKey)) {

                if ($apiKey == $api_key) {

                    $this->flag = true;
                } else {


                    $error = array('status' => 'Failed', 'errorcode' => '-12', 'msg' => 'API key not match');

                    print_r(json_encode($error));

                    exit;
                }
            } else {

                $error = array('status' => 'Failed', 'errorcode' => '-12', 'msg' => 'API key is Missing');

                print_r(json_encode($error));

                exit;
            }
        } else {

            $error = array('status' => 'Failed', 'errorcode' => '-123', 'msg' => 'Required Parameter Missing');

            print_r(json_encode($error));

            exit;
        }
    }

    public function genRandomToken() {

        global $DBH;

        $acess_token = substr(str_shuffle(MD5(microtime())), 0, 15);

        $checkDevice = $this->db->get_where('hoo_devices', array('accessToken' => $acess_token));

        //$checkDevice = Device::model()->findByAttributes(array('varAccessToken' => $acess_token));

        if (!empty($checkDevice)) {

            $acessToken = substr(str_shuffle(MD5(microtime())), 0, 15);
        } else {
            $acessToken = $acess_token;
        }

        return $acessToken;
    }

    public function generateAccessToken($intUdId, $accessToken) {
        //$headers = apache_request_headers();



        if (!empty($accessToken)) {

            $currentDate = date('Y-m-d H:i:s'); // current date



            if (!empty($intUdId)) {

                $deviceModelData = $this->db->get_where('hoo_devices', array('intUdId' => $intUdId));

                $deviceModelDataArr = $deviceModelData->row_array();



                if (!empty($deviceModelDataArr)) {

                    $intDeviceId = $deviceModelDataArr['deviceID'];

                    $access_TokenNew = $deviceModelDataArr['accessToken'];

                    $dateDb = $deviceModelDataArr['createdOn'];

                    if ($access_TokenNew == $accessToken) {

                        $twoHourDiff = strtotime("+2 hour", strtotime($dateDb));

                        $afterTwoHourDate = date("Y-m-d H:i:s", $twoHourDiff);

                        if ($currentDate >= $afterTwoHourDate) {

                            $updateDevice = $this->db->get_where('hoo_devices', array('deviceID' => $intDeviceId));

                            $updateDeviceArr = $updateDevice->row_array();

                            $token = $this->genRandomToken();

                            $updateDeviceArr['createdOn'] = $currentDate;

                            $updateDeviceArr['accessToken'] = $token;

                            $updateData = $this->db->update('hoo_devices', $updateDeviceArr, array('deviceID' => $intDeviceId));

                            if ($updateData === TRUE) {

                                return $token;
                            } else {

                                return '';
                            }
                        } else {

                            return $accessToken;
                        }
                    } else {

                        return '';
                    }
                } else {

                    $error = array('status' => 'Failed', 'errorcode' => '-114', 'msg' => 'Required Parameter Missing');

                    print_r(json_encode($error));
                }// no data of this udid in device
            } else {

                $error = array('status' => 'Failed', 'errorcode' => '-115', 'msg' => 'Required Parameter Missing');

                print_r(json_encode($error));
            }// User id Missing
        } else {

            $error = array('status' => 'Failed', 'errorcode' => '-11', 'msg' => 'Access Token missing from header');

            print_r(json_encode($error));
        }// Access Token Parameter missing...
        //return $error;
    }

    public static function genAccessToken($intUdId) {

        $headers = apache_request_headers();



        if (!empty($headers['accessTokenNew'])) {

            $accessTokenNew = $headers['accessTokenNew']; // Header accessToken

            $currentDate = date('Y-m-d H:i:s'); // current date



            if (!empty($intUdId)) {

                $deviceModelData = $this->db->get_where('hoo_devices', array('intUdId' => $intUdId));

                $deviceModelDataArr = $deviceModelData->row_array();

                if (!empty($deviceModelDataArr)) {

                    $intDeviceId = $deviceModelDataArr['deviceID'];

                    $access_TokenNew = $deviceModelDataArr['accessToken'];

                    $dateDb = $deviceModelDataArr['createdOn'];

                    if ($access_TokenNew == $accessTokenNew) {

                        $twoHourDiff = strtotime("+2 hour", strtotime($dateDb));

                        $afterTwoHourDate = date("Y-m-d H:i:s", $twoHourDiff);



                        if ($currentDate >= $afterTwoHourDate) {

                            $updateDevice = $this->db->get_where('hoo_devices', array('deviceID' => $intDeviceId));

                            $updateDeviceArr = $updateDevice->row_array();

                            $token = $this->genRandomToken();



                            $updateDeviceArr['createdOn'] = $currentDate;

                            $updateDeviceArr['accessToken'] = $token;



                            $updateData = $this->db->update('hoo_devices', $updateDeviceArr, array('deviceID' => $intDeviceId));



                            if ($updateData === FALSE) {

                                $error = array('status' => 'Failed', 'errorcode' => '-2', 'msg' => 'New accesstoken generate failed after accesstoken Expired');

                                print_r(json_encode($error));
                            } else {

                                $error = array('status' => 'True', 'errorcode' => '-1', 'msg' => 'Access Token Expired', 'RandomToken' => $token);

                                print_r(json_encode($error));
                            }
                        } else {

                            $error = array('status' => 'True', 'errorcode' => '-1', 'msg' => 'Accesstoken not Expired', 'RandomToken' => $accessTokenNew);

                            print_r(json_encode($error));
                        }
                    } else {

                        //To delete usre from tbl_device if user accesstoken not matche



                        $DeleteDevice = $this->db->get_where('hoo_devices', array('deviceID' => $intDeviceId));

                        $DeleteDeviceArr = $DeleteDevice->row_array();

                        if (!empty($DeleteDeviceArr)) {

                            $this->db->delete('hoo_devices', array('deviceID' => $intDeviceId));

                            $error = array('status' => 'Failed', 'errorcode' => '-1', 'msg' => 'Please LogIn again..');
                            print_r(json_encode($error));
                        } else {

                            $this->db->delete('hoo_devices', array('deviceID' => $intDeviceId));

                            $error = array('status' => 'Failed', 'errorcode' => '-1', 'msg' => 'Please LogIn again..');

                            print_r(json_encode($error));
                        }
                    }// Please login again and delete user
                } else {

                    $error = array('status' => 'Failed', 'errorcode' => '-111', 'msg' => 'Required Parameter Missing');

                    print_r(json_encode($error));
                }// no data of this udid in device
            } else {

                $error = array('status' => 'Failed', 'errorcode' => '-112', 'msg' => 'Required Parameter Missing');

                print_r(json_encode($error));
            }// User id Missing
        } else {

            $error = array('status' => 'Failed', 'errorcode' => '-11', 'msg' => 'Access Token missing from header');

            print_r(json_encode($error));
        }// Access Token Parameter missing...

        return $error;
    }

}
