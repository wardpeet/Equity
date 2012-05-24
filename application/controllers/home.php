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
                array('id' => 5, 'image' => base_url('images/image03.jpg'), 'title' => 'image 05'),
                array('id' => 6, 'image' => base_url('images/image02.jpg'), 'title' => 'image 06'),
            );

            $return['next'] = true;
            $return['data'] = $images;
        } else {
            $return['next'] = false;
            $return['data'] = array(
                'type' => 1,
                'pictures' => array(array('title' => 'image 01', 'image' => base_url('images/image01.jpg')),
                    array('title' => 'image 02', 'image' => base_url('images/image02.jpg')),
                    array('title' => 'image 03', 'image' => base_url('images/image03.jpg')),
                    array('title' => 'image 04', 'image' => base_url('images/image04.jpg')),
                    array('title' => 'image 05', 'image' => base_url('images/image01.jpg')),
                    array('title' => 'image 06', 'image' => base_url('images/image02.jpg')))
            );
        }

        echo json_encode($return);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */