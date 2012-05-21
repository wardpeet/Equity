<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
    public function index() {
        $this->load->view('home');
    }

    public function next() {
        $return = array();
        $category = $this->input->get('category');

        if ($category == 2) {
            $images = array(
                array('id' => 5, 'image' => base_url('images/image03.jpg'), 'title' => 'image03'),
                array('id' => 6, 'image' => base_url('images/image02.jpg'), 'title' => 'image02'),
            );

            $return['next'] = true;
            $return['data'] = $images;
        }

        echo json_encode($return);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */