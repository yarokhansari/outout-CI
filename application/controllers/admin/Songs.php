<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Songs extends CI_Controller {


	public function __construct() {
        parent::__construct();

		$this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');

        $this->load->model('Common');
        $this->load->helper(array('form', 'url'));
      
    }

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{

		$condition = array(); 

		$allSongs = $this->Common->select_data_by_condition('hoo_songs', array('is_delete' => '0') ,'*','id','DESC','','',array());
		$data['allSongs'] = $allSongs;

		$data['main_content'] = 'admin/song/index';
        $this->load->view('includes/template', $data);
	}

	public function add()
	{

		if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('title', 'Song Title', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect('admin/Songs/add', 'refresh');
            } else 
            {

				if (empty($_FILES['songupload']['name'])){

    				$this->form_validation->set_rules('songupload', 'Upload Song', 'required');

				}else{

					$config['upload_path'] = $this->config->item('upload_path_songs');
					$config['allowed_types'] = $this->config->item('upload_songs_allowed_types');
					$config['file_ext_tolower'] = 'TRUE';
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					
					if ( ! $this->upload->do_upload('songupload')) {
						$error = array('error' => $this->upload->display_errors());
						$this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    	redirect($_SERVER['HTTP_REFERER'], 'refresh');
					} else {
						$songs_array = array('upload_data' => $this->upload->data());
						$type = $songs_array['upload_data']['file_type'];
						$extension = $songs_array['upload_data']['file_ext'];
						$name = $songs_array['upload_data']['file_name'];
					}

				}

              
				$song = array(
                    "title" => $this->input->post('title', TRUE),
					"type" => $type,
					"extension" => $extension,
                    "name" => $name,
					"created_at" => date('Y-m-d h:i:s'),
                );
                $songs = $this->Common->insert_data_getid($song, 'hoo_songs');
				if ($songs) {
                    $this->session->set_flashdata('success', 'Song is added successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }
			}
		}

		$data['main_content'] = 'admin/song/add';
        $this->load->view('includes/template', $data);
	}

	/**
	 * Edit Songs based on id
	 */


	public function edit($id)
	{

		if ($id == '') {
           
			echo '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-ban"></i> Alert!</h4>
					<strong>Error!</strong> No record found.
				  </div>';
            die();
        }else{
			
			$data['info'] = $this->Common->select_data_by_condition('hoo_songs', array( 'id' => $id), '*', '', '', '', '', array());

		}

		$data['main_content'] = 'admin/song/edit';
        $this->load->view('includes/template', $data);
	}

	/**
	 * Update Songs based on id
	 */

	 public function update(){

		if ($this->input->method() == 'post') {
			$this->form_validation->set_rules('title', 'Song Title', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            } else {
				$id = $this->input->post('id', TRUE);
				$songdata = $this->Common->select_data_by_condition('hoo_songs', array( 'id' => $id), '*', '', '', '', '', array());

				if (empty($_FILES['songupload']['name'])){

					$this->form_validation->set_rules('songupload', 'Upload Song', 'required');
	
				}else{
	
					$config['upload_path'] = $this->config->item('upload_path_songs');
					$config['allowed_types'] = $this->config->item('upload_songs_allowed_types');
					$config['file_ext_tolower'] = 'TRUE';
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					
					if ( ! $this->upload->do_upload('songupload')) {
						$error = array('error' => $this->upload->display_errors());
						$this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					} else {
						$songs_array = array('upload_data' => $this->upload->data());
						$type = $songs_array['upload_data']['file_type'];
						$extension = $songs_array['upload_data']['file_ext'];
						$name = $songs_array['upload_data']['file_name'];
					}
	
				}

				if ($this->config->item('upload_path_songs') . $songdata[0]['name']) {
					if (file_exists($this->config->item('upload_path_songs') . $songdata[0]['name'])) {
                    	@unlink($this->config->item('upload_path_songs') . $songdata[0]['name']);
                 	}
				}

				$song = array(
                    "title" => $this->input->post('title', TRUE),
					"type" => $type,
					"extension" => $extension,
                    "name" => $name,
					"updated_at" => date('Y-m-d h:i:s'),
                );

				if ($this->Common->update_data($song, 'hoo_songs', 'id', $id)) {
                    $this->session->set_flashdata('success', 'Song is Updated successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }


			}
		}

	 }

	/**
	 * Delete Songs based on id
	 */

	public function delete($id){

		if ($id == '') {
           
			echo '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-ban"></i> Alert!</h4>
					<strong>Error!</strong> No record found.
				  </div>';
            die();
        }else{

			$songdata = $this->Common->select_data_by_condition('hoo_songs', array("id" => $id), '*', '', '', '', '', array());

			$delete_song = array(
				"is_delete" => '1',
			);
			if ($this->Common->update_data($delete_song, 'hoo_songs', 'id', $id)) {
				if ($this->config->item('upload_path_songs') . $songdata[0]['name']) {
					if (file_exists($this->config->item('upload_path_songs') . $songdata[0]['name'])) {
                    	@unlink($this->config->item('upload_path_songs') . $songdata[0]['name']);
                 	}
				}
				$this->session->set_flashdata('success', 'Song is deleted successfully.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			} else {
				$this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}

		}

	}
}
