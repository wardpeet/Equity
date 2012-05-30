<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
    
    const TYPE_SLIDER = 1;
    const TYPE_SCHEME = 2;
    
    
    public function index() {
        $cats = Doctrine::getInstance()->getRepository('Entities\Category')->getRootCatAndChildren(1);//1 = language id
        $main = array();
        $subs = array();
        foreach($cats AS $category){
            if(is_null($category->getParent())){
                $main = array('id' => $category->getId(), 'image' => base_url('images/'.$category->getId().'.jpg'), 'title' => $category->getName());
            }else{
                $subs[] = array('id' => $category->getId(), 'image' => base_url('images/'.$category->getId().'.jpg'), 'title' => $category->getName());
            }
        }
        $data = array('main' => $main, 'subs' => $subs);
        $this->load->view('home', $data);
    }

    public function next() {
        $return = array('next' => true);
        $category = $this->input->get('category');
        if(!$category && !is_numeric($category)){
            return;
        }
/*        if ($category == 2) {
            $images = array(
                array('id' => 5, 'image' => base_url('images/image03.jpg'), 'title' => 'image 05'),
                array('id' => 6, 'image' => base_url('images/image02.jpg'), 'title' => 'image 06'),
            );

            $return['next'] = true;
            $return['data'] = $images;
        } else {
            
            // type 1
            /*$return['next'] = false;
            $return['data'] = array(
                'type' => 1,
                'pictures' => array(array('title' => 'image 01', 'image' => base_url('images/image01.jpg')),
                    array('title' => 'image 02', 'image' => base_url('images/image02.jpg')),
                    array('title' => 'image 03', 'image' => base_url('images/image03.jpg')),
                    array('title' => 'image 04', 'image' => base_url('images/image04.jpg')),
                    array('title' => 'image 05', 'image' => base_url('images/image01.jpg')),
                    array('title' => 'image 06', 'image' => base_url('images/image02.jpg')))
            );*/
            // type 2
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
        }*/
        
        $cats = Doctrine::getInstance()->getRepository('Entities\Category')->getChildren($category);
        $return = array('next' => false, 'data' => array());
        foreach($cats AS $category){
            $return['next'] = true;
            $return['data'][] = array('id' => $category->getId(), 'image' => base_url('images/'.$category->getId().'.jpg'), 'title' => $category->getName());
        }
        if(!$return['next']){
            $ret = Doctrine::getInstance()->getRepository('Entities\Category')->getTextAndType($category);
            $ret = $ret[0];
            switch($ret->getType()){
                case self::TYPE_SLIDER:
                    $return['data'] = unserialize($ret->getText());
                    break;
                case self::TYPE_SCHEME:
                    $return['data'] = unserialize($ret->getText());
                    break;
            }
            $return['data'] = unserialize($ret->getText());
            $return['data']['type'] = $ret->getType();
        }
        echo json_encode($return);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
