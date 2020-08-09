<?php

class Video_model extends CI_Model
{
	function store_video_data($data){
		$insert_data['title'] = $data['title'];
		$insert_data['description'] = $data['description'];
		$insert_data['videoContent'] = $data['videoContent'];
		$insert_data['uploadedDate'] = date('Y-m-d');

		$query = $this->db->insert('video', $insert_data);
	}

	function get_all_videos()
	{
		$all_videos = $this->db->get('video');
		return $all_videos->result();
	}

	function get_video($videoContent)
	{
		$this->db->select('*');
		$this->db->from('video');
		$this->db->where(array('videoContent' => $videoContent));
		$query = $this->db->get();
		return $query->row();
	}

	function get_video_by_title($title)
	{
		$this->db->select('*');
		$this->db->from('video');
		$this->db->where(array('title' => $title));
		$query = $this->db->get();
		return $query->row();
	}

	public function showAllComments(){
		$this->db->order_by('created_at', 'desc');
		$query = $this->db->get('comment');
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function addComment(){
		$field = array(
			'content'=>$this->input->post('content'),
			'username'=>$this->input->post('username'),
			'created_at'=>date('Y-m-d H:i:s')
		);
		$this->db->insert('comment', $field);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	function deleteComment(){
		$comment_id = $this->input->get('comment_id');
		$this->db->where('comment_id', $comment_id);
		$this->db->delete('comment');
		if($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	function getRows($params = array()){
		$this->db->select('*');
		$this->db->from('video');

		if(array_key_exists("where", $params)){
			foreach($params['where'] as $key => $val){
				$this->db->where($key, $val);
			}
		}

		if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
			$result = $this->db->count_all_results();
		}else{
			if(array_key_exists("videoID", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')){
				if(!empty($params['videoID'])){
					$this->db->where('videoID', $params['videoID']);
				}
				$query = $this->db->get();
				$result = $query->row_array();
			}else{
				$this->db->order_by('videoID', 'desc');
				if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
					$this->db->limit($params['limit'],$params['start']);
				}elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
					$this->db->limit($params['limit']);
				}

				$query = $this->db->get();
				$result = ($query->num_rows() > 0)?$query->result_array():FALSE;
			}
		}

		return $result;
	}
}
