<?php

class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if ($_SESSION['user_logged'] == false) {
			redirect("auth/login");
		}

	}

	public function profile()
	{
		$this->load->view("header");
		$this->load->view('profile');
		$this->load->view("footer");
	}

	public function edit()
	{
		// update profile photo.
		if (isset($_POST["check"])) {
			$this->load->library('image_lib');

			//set file upload settings
			$config['upload_path']          = APPPATH. '../assets/profilePhotosUploaded';
			$config['allowed_types']        = 'jpg|png|jpeg';
			$config['max_size']             = 10000;

			$this->load->library('upload', $config);

			if (! $this->upload->do_upload('profilePhoto')) {
				$error = $this->upload->display_errors();
				$this->session->set_flashdata("error", $error);
				redirect("user/edit", "refresh");
			} else {
				$upload_data = $this->upload->data();
				$path = APPPATH. '../assets/profilePhotosUploaded/' .$upload_data['file_name'];

				$configImg['image_library'] = 'gd2';
				$configImg['source_image'] = $path;
				$configImg['create_thumb'] = false;
				$configImg['maintain_ratio'] = true;
				$configImg['width'] = 150;
				$configImg['height'] = 150;
				$configImg['wm_overlay_path'] = APPPATH. '../assets/profilePhotosUploaded/watermark.png';
				$configImg['wm_type'] = 'overlay';
				$configImg['wm_opacity'] = '20';
				$configImg['wm_vrt_alignment'] = 'bottom';
				$configImg['wm_hor_alignment'] = 'center';

				$this->image_lib->initialize($configImg);

				if (! $this->image_lib->resize()) {
					echo $this->image_lib->display_errors();
				} else {
					echo "resize done!";
				}

				if (! $this->image_lib->watermark()) {
					echo $this->image_lib->display_errors();
				} else {
					echo "watermark done!";
				}

				$_SESSION["profilePhoto"] = $upload_data['file_name'];
				$this->image_lib->clear();

			}
		}

		// update profile information.
		if (isset($_POST["update"])) {

			$this->form_validation->set_rules('phone', 'phone', 'min_length[10]' ,[
				'min_length' => 'This phone number is not valid.'
			]);

			if ($this->form_validation->run() == true) {
				$data = array(
					'username' => $_POST['username'],
					'phone' => $_POST['phone'],
					'profilePhoto' => $_SESSION["profilePhoto"]
				);

				$this->db->where('email', $_SESSION['email']);
				$this->db->update('user', $data);

				$_SESSION['username'] = $_POST['username'];
				$_SESSION['phone'] = $_POST['phone'];

				$this->session->set_flashdata("success", "Your have updated your profile.");
				redirect("user/profile", "refresh");
			}
		}

		$this->load->view("header");
		$this->load->view('editProfile');
		$this->load->view("footer");
	}
}
