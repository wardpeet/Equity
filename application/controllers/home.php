<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
    
    public function index() {
        $this->load->view('home');
        var_dump($this->doctrine->em->getRepository('Entities\Category')->getCatAndChildren(1));
    }

    public function next() {
        $return = array('next' => true);
        $category = $this->input->get('category');
        if(!$category){
            return;
        }
        if ($category == 2) {
            $images = array(
                array('id' => 5, 'image' => base_url('images/image03.jpg'), 'title' => 'image 05'),
                array('id' => 6, 'image' => base_url('images/image02.jpg'), 'title' => 'image 06'),
            );

            $return['next'] = true;
            $return['data'] = $images;
        } else {
            $return['next'] = false;
            $return['data'] = array('content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi vehicula gravida nunc ut semper. Mauris tincidunt ipsum euismod neque tincidunt sodales. In eget eros vel leo dictum bibendum a a erat. Aenean sed commodo massa. Nunc non tristique urna. Morbi nec diam erat, quis ornare lorem. Nulla in tellus id est faucibus scelerisque. Donec purus purus, rhoncus id suscipit sit amet, ornare ut ipsum. Sed bibendum auctor lobortis.</p>
<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer accumsan lectus ac ante bibendum in pellentesque erat tempor. Proin molestie laoreet eros non venenatis. Nullam venenatis, est porttitor venenatis congue, dui libero interdum leo, ut mattis sem sem sed tellus. Cras molestie, urna et laoreet convallis, tellus libero pharetra urna, vulputate commodo lectus sapien quis dolor. Curabitur ac nunc urna. Nunc laoreet eleifend tellus ut congue. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Duis est diam, ultricies vitae lobortis a, blandit a leo. Fusce feugiat molestie elit quis ultricies. Aenean pharetra est id quam feugiat eget hendrerit mauris condimentum. Fusce felis lorem, pretium et vestibulum nec, dapibus id dolor. Morbi et eros mauris. Nullam nec nisi vel sem condimentum sagittis ac nec orci.</p>
<p>Morbi eleifend interdum mauris. Phasellus nunc nunc, vehicula ac fringilla in, consectetur sed felis. Aenean sit amet tristique enim. Sed lobortis, magna at sagittis ullamcorper, risus nulla fringilla dolor, fermentum accumsan dui ante ut mauris. Nullam tincidunt risus sit amet magna rutrum at egestas felis imperdiet. Donec fermentum vehicula ultrices. Fusce porta, quam ut pharetra mollis, velit urna elementum odio, vitae aliquet orci dui a augue.</p>
<p>Sed sed libero eu libero iaculis eleifend. Nam ultricies sapien at quam tincidunt blandit. Vestibulum diam arcu, lacinia vitae porttitor consectetur, euismod at risus. Donec laoreet orci quis libero bibendum id vulputate leo aliquet. Pellentesque tempus porta justo eu ornare. Mauris vel varius tellus. Maecenas eu lorem elit, quis fringilla metus.</p>
<p>Aenean rhoncus tristique dignissim. Vestibulum iaculis urna nunc, quis ullamcorper nibh. Vivamus tempor scelerisque purus ut porta. Donec viverra ultricies nisi, ut venenatis nibh vestibulum quis. Curabitur ipsum justo, mollis vel consequat ut, ornare id eros. Suspendisse porttitor ullamcorper ipsum a hendrerit. Mauris sed ante vitae felis sodales adipiscing. Cras eros diam, commodo sit amet adipiscing in, vehicula vel lectus. Donec sodales, metus porta porta tincidunt, ipsum lectus malesuada ipsum, eget volutpat felis risus sit amet urna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Pellentesque sodales lacinia lobortis.</p>');
        }

        echo json_encode($return);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */