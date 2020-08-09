<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends CI_Controller{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Video_model');
		$this->load->library('pagination');
		$this->perPage = 6;
	}

	public function back()
	{
		$referred_from = $this->session->userdata('referred_from');
		redirect($referred_from, 'refresh');
	}

	public function upload()
	{
		$this->load->view("header");
		$this->load->view('upload');
		$this->load->view("footer");
	}

	public function video_data()
	{
		//validate the form data
		$this->form_validation->set_rules('title', 'Video Title', 'required');
		if ($this->form_validation->run() == FALSE){
			$this->load->view('upload');
		} else {

			//get the form values
			$data['title'] = $this->input->post('title', TRUE);
			$data['description'] = $this->input->post('description', TRUE);
			//file upload code
			//set file upload settings
			$config['upload_path']          = APPPATH. '../assets/videosUploaded';
			$config['allowed_types']        = 'mp4';
			$config['max_size']             = 10000;
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('videoContent')){
				$error = array('error' => $this->upload->display_errors());
				$this->load->view("header");
				$this->load->view('upload', $error);
				$this->load->view("footer");
			}else{
				//file is uploaded successfully
				//now get the file uploaded data
				$upload_data = $this->upload->data();
				//get the uploaded file name
				$data['videoContent'] = $upload_data['file_name'];
				//store data to the db
				$this->Video_model->store_video_data($data);
				$this->session->set_flashdata("success", "Your video uploaded successfully!");
				redirect('', 'refresh');
			}
		}
	}

	public function video_collection()
	{
		$data = $conditions = array();
		$uriSegment = 3;

		// Get record count
		$conditions['returnType'] = 'count';
		$totalRec = $this->Video_model->getRows($conditions);

		// Pagination configuration
		$config['base_url']    = base_url().'video/video_collection';
		$config['uri_segment'] = $uriSegment;
		$config['total_rows']  = $totalRec;
		$config['per_page']    = $this->perPage;

		// Pagination link format
		$config['num_tag_open'] = '<li style="padding: 8px">';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0);">';
		$config['cur_tag_close'] = '</a></li>';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Prev';
		$config['next_tag_open'] = '<li class="pg-next">';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="pg-prev">';
		$config['prev_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		// Initialize pagination library
		$this->pagination->initialize($config);

		// Define offset
		$page = $this->uri->segment($uriSegment);
		$offset = !$page?0:$page;

		// Get records
		$conditions = array(
			'start' => $offset,
			'limit' => $this->perPage
		);
		$data['videos_list'] = $this->Video_model->getRows($conditions);

		// $data['videos_list'] = $this->Video_model->get_all_videos();
		$this->load->view("header");
		$this->load->view('videosCollection', $data);
		$this->load->view("footer");
	}

	// 5.3 update
	public function video_display($videoContent)
	{
		$video = $this->Video_model->get_video($videoContent);

		$videoID = $video->videoID; // It will be changed with dynamic value

		// Fetch the post and rating info from database
		$connect = mysqli_connect("localhost", "root", "01637bc38f2087ee", "yodude");
		$query = "SELECT v.*, COUNT(r.rating_number) as rating_num, FORMAT((SUM(r.rating_number) / COUNT(r.rating_number)),1) as average_rating 
					FROM video as v LEFT JOIN rating as r ON r.video_id = v.videoID 
					WHERE v.videoID = $videoID GROUP BY (r.video_id)";
		$result = mysqli_query($connect, $query);
		$postData = $result->fetch_assoc();

		$data['postData'] = $postData;
		$this->load->view("header");
		$this->load->view('videoDisplay',$data);
		$this->load->view("footer");
	}

	// comment video
	public function showAllComments(){
		$result = $this->Video_model->showAllComments();
		echo json_encode($result);
	}

	public function addComment(){
		$result = $this->Video_model->addComment();
		$msg['success'] = false;
		$msg['type'] = 'add';
		if($result){
			$msg['success'] = true;
		}
		echo json_encode($msg);
	}

	public function deleteComment(){
		$result = $this->Video_model->deleteComment();
		$msg['success'] = false;
		if($result){
			$msg['success'] = true;
		}
		echo json_encode($msg);
	}

	public function autoComplete(){
		if(isset($_POST["query"]))
		{
			$connect = mysqli_connect("localhost", "root", "01637bc38f2087ee", "yodude");
			$query = "SELECT * FROM video WHERE title LIKE '%".$_POST["query"]."%'";
			$result = mysqli_query($connect, $query);

			$output = '<ul class="list-unstyled" style="background-color: #eeeeee; cursor: pointer">';
			if(mysqli_num_rows($result) > 0)
			{
				while($row = mysqli_fetch_array($result))
				{
					$output .= '<li style="padding: 12px">'.$row["title"].'</li>';
				}
			}
			else
			{
				$output .= '<li style="padding: 12px">Video Not Found</li>';
			}
			$output .= '</ul>';
			echo $output;
		}
	}

	// 5.6 search funciton
	public function search() {
		$title = $_POST['video'];
		$video = $this->Video_model-> get_video_by_title($title);

		if ($video != null) {
			$videoID = $video->videoID;
			// Fetch the post and rating info from database
			$connect = mysqli_connect("localhost", "root", "01637bc38f2087ee", "yodude");
			$query = "SELECT v.*, COUNT(r.rating_number) as rating_num, FORMAT((SUM(r.rating_number) / COUNT(r.rating_number)),1) as average_rating 
					FROM video as v LEFT JOIN rating as r ON r.video_id = v.videoID 
					WHERE v.videoID = $videoID GROUP BY (r.video_id)";
			$result = mysqli_query($connect, $query);
			$postData = $result->fetch_assoc();

			$data['postData'] = $postData;
			$this->load->view("header");
			$this->load->view('videoDisplay',$data);
			$this->load->view("footer");
		} else {
			header("location:https://infs3202-2904dd11.uqcloud.net/yodude/");
		}

	}

	// 5.3 rating
    public function rating() {
		if(!empty($_POST['videoID']) && !empty($_POST['ratingNum'])){
			// Get posted data
			$videoID = $_POST['videoID'];
			$ratingNum = $_POST['ratingNum'];

			// Current IP address
			$userIP = $_SERVER['REMOTE_ADDR'];

			// Check whether the user already submitted the rating for the same post
			$connect = mysqli_connect("localhost", "root", "01637bc38f2087ee", "yodude");
			$query = "SELECT rating_number FROM rating WHERE video_id = $videoID AND user_ip = '".$userIP."'";
			$result = mysqli_query($connect, $query);

			if($result->num_rows > 0){
				// Status
				$status = 2;
			}else{
				// Insert rating data in the database
				$query = "INSERT INTO rating (video_id,rating_number,user_ip) VALUES ('".$videoID."', '".$ratingNum."', '".$userIP."')";
				$insert = mysqli_query($connect, $query);

				// Status
				$status = 1;
			}

			// Fetch rating details from the database
			$query = "SELECT COUNT(rating_number) as rating_num, FORMAT((SUM(rating_number) / COUNT(rating_number)),1) as average_rating 
						FROM rating 
						WHERE video_id = $videoID GROUP BY (video_id)";
			$result = mysqli_query($connect, $query);
			$ratingData = $result->fetch_assoc();

			$response = array(
				'data' => $ratingData,
				'status' => $status
			);

			// Return response in JSON format
			echo json_encode($response);
		}
	}

}
