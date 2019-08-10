<?php
	class Post_model extends CI_Model{
		public function __construct(){
			$this->load->database();
		}

		public function get_posts($slug = FALSE, $limit = FALSE, $offset = FALSE){
			if($limit){
				$this->db->limit($limit, $offset);
			}
			if($slug === FALSE){
				$this->db->order_by('posts.id', 'DESC');
				$this->db->join('categories', 'categories.id = posts.category_id');
				$query = $this->db->get('posts');
				return $query->result_array();
			}


			$query = $this->db->get_where('posts', array('slug' => $slug));
			return $query->row_array();
		}

		public function get_years($year)
		{
			if($year!=0)
			{
			$sql="SELECT DATE_FORMAT(created_at, '%Y') as year FROM posts where DATE_FORMAT(created_at, '%Y')=$year GROUP BY year ";
			$query=$this->db->query($sql);
			return $query->result_array();
			}
			else
			$sql="SELECT DATE_FORMAT(created_at, '%Y') as year FROM posts GROUP BY year";
			$query=$this->db->query($sql);
			return $query->result_array();
		}

		public function get_months($year)
		{
			$sql="SELECT DATE_FORMAT(created_at, '%M') as month FROM posts where DATE_FORMAT(created_at, '%Y')=$year group by month";
			$query=$this->db->query($sql);
			return $query->result_array();
		}

		public function get_my_posts($slug = FALSE, $limit = FALSE, $offset = FALSE){
			if($limit){
				$this->db->limit($limit, $offset);
			}
			$user_id=$this->session->userdata('user_id');
			if($slug === FALSE){
				$this->db->order_by('posts.id', 'DESC');
				$this->db->join('categories', 'categories.id = posts.category_id');
				$this->db->where('posts.user_id =',$user_id);
				$query = $this->db->get('posts');
				return $query->result_array();
			}
			
			
			$query = $this->db->get_where('posts', array('user_id' => $user_id));
			return $query->row_array();
		}

		public function get_post_year($year){
			$slug = FALSE; $limit = FALSE; $offset = FALSE;
			if($limit){
				$this->db->limit($limit, $offset);
			}
		//	print_r($year);
			$user_id=$this->session->userdata('user_id');
			if($slug === FALSE){
				$this->db->select('*');
				$this->db->from('posts');
				$this->db->order_by('posts.id', 'DESC');
				$this->db->join('categories', 'categories.id = posts.category_id');
				$this->db->where("DATE_FORMAT(posts.created_at, \"%Y\") =", $year);
				$query = $this->db->get();
				//$this->db->where("DATE_FORMAT(create_at, '%Y') =", 2017);
				
				return $query->result_array();
			}
			
			
			$query = $this->db->get_where('posts', array('user_id' => $user_id));
			return $query->row_array();
		}
		public function get_post_month($year,$month){
			$slug = FALSE; $limit = FALSE; $offset = FALSE;
			if($limit){
				$this->db->limit($limit, $offset);
			}
		//	print_r($year);
			$user_id=$this->session->userdata('user_id');
			if($slug === FALSE){
				$this->db->select('*');
				$this->db->from('posts');
				$this->db->order_by('posts.id', 'DESC');
				$this->db->join('categories', 'categories.id = posts.category_id');
				$this->db->where("DATE_FORMAT(posts.created_at, \"%Y\") =", $year);
				$this->db->where("DATE_FORMAT(posts.created_at, \"%m\") =", $month);
				$query = $this->db->get();
				//$this->db->where("DATE_FORMAT(create_at, '%Y') =", 2017);
				
				return $query->result_array();
			}
			
			
			$query = $this->db->get_where('posts', array('user_id' => $user_id));
			return $query->row_array();
		}
		

		public function create_post($post_image){
			$slug = url_title($this->input->post('title'));

			$data = array(
				'title' => $this->input->post('title'),
				'slug' => $slug,
				'body' => $this->input->post('body'),
				'category_id' => $this->input->post('category_id'),
				'user_id' => $this->session->userdata('user_id'),
				'post_image' => $post_image
			);

			return $this->db->insert('posts', $data);
		}

		public function delete_post($id){
			$image_file_name = $this->db->select('post_image')->get_where('posts', array('id' => $id))->row()->post_image;
			$cwd = getcwd(); // save the current working directory
			$image_file_path = $cwd."\\assets\\images\\posts\\";
			chdir($image_file_path);
			unlink($image_file_name);
			chdir($cwd); // Restore the previous working directory
			$this->db->where('id', $id);
			$this->db->delete('posts');
			return true;
		}

		public function update_post(){
			$slug = url_title($this->input->post('title'));

			$data = array(
				'title' => $this->input->post('title'),
				'slug' => $slug,
				'body' => $this->input->post('body'),
				'category_id' => $this->input->post('category_id')
			);

			$this->db->where('id', $this->input->post('id'));
			return $this->db->update('posts', $data);
		}

		public function get_categories(){
			$this->db->order_by('name');
			$query = $this->db->get('categories');
			return $query->result_array();
		}

		public function get_posts_by_category($category_id){
			$this->db->order_by('posts.id', 'DESC');
			$this->db->join('categories', 'categories.id = posts.category_id');
				$query = $this->db->get_where('posts', array('category_id' => $category_id));
			return $query->result_array();
		}
	}