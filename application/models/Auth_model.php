<?php

class Auth_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// send email.
	public function sendVerificatinEmail($email,$verificationText){

		$config = array (
			'protocol' => 'smtp',
			'smtp_host' => 'mailhub.eait.uq.edu.au',
			'smtp_port' => 25,
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'wordwrap' => TRUE
		);

		if ($this->load->library('email', $config)) {
			echo "loaded success";
		} else {
			echo "config wrong";
		}
		$this->email->set_newline("\r\n");
		$this->email->from('zhiyu.he@uqconnect.edu.au', "Yodude");
		$this->email->to($email);
		$this->email->subject("Email Verification");

		$this->email->message("Dear User,\r\nPlease click on below URL or paste into your browser to verify your Email Address
			\r\n https://infs3202-2904dd11.uqcloud.net/yodude/auth/validate_email/".$email ."/" .$verificationText."\r\n"."\r\nThanks\r\nYodude");
		if ($this->email->send()) {
			echo "send successfully";
		} else {
			echo "send failed.";
		}
	}

	// check and activate account.
	public function validate($email_address, $email_code) {
		$this->db->select('email, emailVerifyCode, username');
		$this->db->from('user');
		$this->db->where(array('email' => $email_address));
		$query = $this->db->get();
		$user = $query->row();

		if ($user->username) {
			if ($user->emailVerifyCode === $email_code) {
				$result = $this->activate_account($email_address);
				if ($result === true) {
					redirect("video/video_collection", "refresh");
					echo "Activating your account successfully.";
					return true;
				} else {
					echo "An error when activating your account.";
					return false;
				}
			} else {
				echo "An error when activating your account.";
			}
		}
	}

	// update status in database
	private function activate_account($email_address) {
		$sql = "UPDATE user SET verifyStatus = 'A' WHERE email = '" .$email_address ."' LIMIT 1";
		$this->db->query($sql);

		if ($this->db->affected_rows() ===1) {
			return true;
		} else {
			echo 'Error in updating database';
			return false;
		}

	}

}
