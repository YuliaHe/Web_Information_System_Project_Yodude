<?php

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Auth_model');
		$this->load->library('encryption');
		$this->load->helper('captcha');
	}

	public function login()
	{
		$this->form_validation->set_rules('captcha', 'Captcha', 'callback_validate_captcha');

		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

		if ($this->form_validation->run() == true) {

			$username = $_POST['username'];
			$password = $_POST['password'];
			
			// check user in database
			$this->db->select('*');
			$this->db->from('user');
			$this->db->where(array('username' => $username, 'password' => base64_encode($password))); // decrypt password
			$query = $this->db->get();
			$user = $query->row();

			// if user exists
			if (!empty($user->email)) {

				// set cookie
				if(!empty($_POST['remember'])) {
					$this->input->set_cookie('username', $username, 86500);
					$this->input->set_cookie('password', $password, 86500);
				} else {
					delete_cookie('username');
					delete_cookie('password');
				}

				// temporary message
				$this->session->set_flashdata("success", "You are logged in");

				// set session variables
				$_SESSION['user_logged'] = true;
				$_SESSION['username'] = $user->username;
				$_SESSION['email'] = $user->email;
				$_SESSION['phone'] = $user->phone;
				$_SESSION['emailStatus'] = $user->verifyStatus;
				$_SESSION['createdDate'] = $user->createdDate;
				$_SESSION['profilePhoto'] = $user->profilePhoto;

				// redirect to profile page
				redirect("user/profile", "refresh");
			}
			else {
				$this->session->set_flashdata("error", "No such account exists. Or username and password are not matched.");
				redirect("auth/login", "refresh");
			}
		} else {
			// Captcha configuration
			$original_string = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
			$original_string = implode("", $original_string);
			$captchaCode = substr(str_shuffle($original_string), 0, 6);

			$config = array(
				'word'			=> $captchaCode,
				'img_path'      => APPPATH. '../assets/captcha/',
				'img_url'       => base_url().'assets/captcha/',
				'img_width'     => 160,
				'img_height'    => 50,
				'word_length'   => 6,
				'font_size'     => 20
			);
			$captcha = create_captcha($config);

			// Unset previous captcha and set new captcha word
			$this->session->unset_userdata('captchaCode');
			$this->session->set_userdata('captchaCode', $captcha['word']);

			// Pass captcha image to view
			$data['captchaImg'] = $captcha['image'];
		}

		$this->load->view('header');
		$this->load->view('login',$data);
		$this->load->view('header');
	}

	public function signup()
	{
		if (isset($_POST['signup'])) {
			$this->form_validation->set_rules('username', 'username', 'required|is_unique[user.username]',[
				'is_unique' => 'This username has been occupied.'
			]);
			$this->form_validation->set_rules('email', 'email', 'required|is_unique[user.email]|valid_email',[
				'is_unique' => 'This email has been registered.',
				'valid_email' => 'This email is not valid'
			]);
			$this->form_validation->set_rules('phone', 'phone', 'required|min_length[10]');
			$this->form_validation->set_rules('password', 'password', 'required|callback_valid_password');
			$this->form_validation->set_rules('comfirm_password', 'comfirm password', 'required|matches[password]');

			// if form validation true
			if ($this->form_validation->run() == true) {

				// Add user in database
				$verifyCode = $this->randomVerifyCode(16); // generate password randomly.
				$data = array(
					'username' => $_POST['username'],
					'email' => $_POST['email'],
					'phone' => $_POST['phone'],
					'password' => base64_encode($_POST['password']), // encrypt password
					'createdDate' => date('Y-m-d'),
					'emailVerifyCode' => $verifyCode,
					'verifyStatus' => 'N'
				);
				$this->db->insert('user', $data);
				$this->Auth_model->sendVerificatinEmail($_POST['email'], $verifyCode); // send email.

				$this->session->set_flashdata("success", "Your account has been registered. You can login and change your profile photo now");
				redirect("auth/login", "refresh");
			}
		}

		// load view
		$this->load->view('header');
		$this->load->view("signup");
		$this->load->view('header');
	}

	// generate a random code to verify email.
	public function randomVerifyCode($length){
		$output='';
		for ($a = 0; $a<$length; $a++) {
			$output .= chr(mt_rand(97, 122));    // generate code containing a-z,A-Z(ascii)
		}
		return $output;
	}

	public function logout()
	{
		unset($_SESSION);
		session_destroy();
		redirect("auth/login", "refresh");
	}

	// After click the verification link.
	public function validate_email($email_address, $email_code) {
		$validated = $this->Auth_model->validate($email_address, $email_code); // go to model.

		if($validated === true) {
			$_SESSION['emailStatus'] = 'A'; // debug later
			redirect("video/video_collection", "refresh");
		} else {
			echo "Error";
		}
	}

	// check strength of pswd.
	public function valid_password($password = '')
	{
		$password = trim($password);

		$regex_lowercase = '/[a-z]/';
		$regex_uppercase = '/[A-Z]/';
		$regex_number = '/[0-9]/';
		$regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';

		if (empty($password))
		{
			$this->form_validation->set_message('valid_password', 'The password is required.');

			return FALSE;
		}

		if (preg_match_all($regex_lowercase, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The password must be at least one lowercase letter.');

			return FALSE;
		}

		if (preg_match_all($regex_uppercase, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The password must be at least one uppercase letter.');

			return FALSE;
		}

		if (preg_match_all($regex_number, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The password must have at least one number.');

			return FALSE;
		}

		if (preg_match_all($regex_special, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The password must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>§~'));

			return FALSE;
		}

		if (strlen($password) < 6)
		{
			$this->form_validation->set_message('valid_password', 'The password must be at least 6 characters in length.');

			return FALSE;
		}

		if (strlen($password) > 32)
		{
			$this->form_validation->set_message('valid_password', 'The password cannot exceed 32 characters in length.');

			return FALSE;
		}

		return TRUE;
	}


	// 5.2 refresh captcha
	public function refresh(){
		// Captcha configuration
		$original_string = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
		$original_string = implode("", $original_string);
		$captchaCode = substr(str_shuffle($original_string), 0, 6);

		$config = array(
			'word'			=> $captchaCode,
			'img_path'      => APPPATH. '../assets/captcha/',
			'img_url'       => base_url().'assets/captcha/',
			'img_width'     => 160,
			'img_height'    => 50,
			'word_length'   => 6,
			'font_size'     => 20
		);
		$captcha = create_captcha($config);

		// Unset previous captcha and set new captcha word
		$this->session->unset_userdata('captchaCode');
		$this->session->set_userdata('captchaCode',$captcha['word']);

		// Display captcha image
		echo $captcha['image'];
	}

	public function validate_captcha()
	{
		if ($this->input->post('captcha') != $this->session->userdata['captchaCode']) {
			$this->form_validation->set_message('validate_captcha', 'Captcha Code is wrong');
			return false;
		} else {
			return true;
		}
	}


}
