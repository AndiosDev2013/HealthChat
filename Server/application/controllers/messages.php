<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends CI_Controller {

    private $__data = null;

    public function Messages()
    {
        parent::__construct();
        $username = $this->session->userdata('user_name');
        
        if ($username == null || empty($username))
            redirect('member/login');
        else
            $this->__data['name'] = $username;

  	    $this->__data['mtype'] = MESSAGES;
        $this->__data['level'] = $this->session->userdata('level');
        $this->__data['unread'] = (int)$this->Data_model->get_msg_count(UNREAD_MSG);
    }

	public function index()
	{
        $config['base_url'] = $this->config->site_url() . '/messages/index/';
        $config['total_rows'] = sizeof($this->Data_model->get_message_infos());
        $config['per_page'] = PER_PAGE; 
        $config['uri_segment'] = 3;

        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = '<<';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '>>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] = '<';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li><a><b>";
        $config['cur_tag_close'] = '</b></a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';            
            
            
                    
        $page = (int)$this->uri->segment(3); 
        
        $this->pagination->initialize($config); 
        
        $this->__data['pagination'] = $this->pagination->create_links();

	    $this->__data['msgs'] = $this->Data_model->get_message_infos(0, PER_PAGE, $page);
        $this->load->view('messages/list', $this->__data);
	}
    
	public function delete()
	{
	   
        $user_id = (int) $this->uri->segment(3);
        
        if ($user_id <= 0)
        {
            redirect('messages');
            return;
        }
	   $this->Data_model->delete_message_info($user_id);
       redirect('messages');
	}
    
    public function detail()
    {
//        $msg_id = (int) $this->uri->segment(3);
//        $this->__data['msg_info'] = $this->Data_model->get_message_info($msg_id);
//        $this->__data['reply_infos'] = $this->Data_model->get_reply_infos($msg_id);
        if ('POST' == $_SERVER['REQUEST_METHOD'])
        {
            $user_id = $this->input->post('user_id');
            $this->Data_model->add_message($user_id, $this->input->post('reply_msg'));
            $this->__data['messages'] = $this->Data_model->get_message_info_by_user_id($user_id);
            $this->__data['is_collapsed'] = $this->input->post('is_collapsed');
            redirect('messages/detail/' . $user_id);
        }
        else
        {
            $user_id = (int) $this->uri->segment(3);
            $this->__data['messages'] = $this->Data_model->get_message_info_by_user_id($user_id);
            $this->Data_model->update_all_msg_status($user_id);
            $this->__data['is_collapsed'] = 0;
        }

        $this->__data['user_id'] = $user_id;
        $this->load->view('messages/detail', $this->__data);
    }
    
    public function reply()
    {
        $user_id = $this->input->post('user_id');
        
        $this->Data_model->add_message($user_id, $this->input->post('reply_msg'));

        $this->__data['is_collapsed'] = $this->input->post('is_collapsed');
        redirect('messages/detail/' . $user_id);
    }
    
/************************ Ajax ***********************/
    public function get_msg_count()
    {
        echo $this->Data_model->get_msg_count(UNREAD_MSG);
    }    
}

/* End of file meber.php */
/* Location: ./application/controllers/member.php */