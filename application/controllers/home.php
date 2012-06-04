<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
    
    const TYPE_SLIDER = 1;
    const TYPE_SCHEME = 2;
    const DEFAULT_LANGUAGE = 1;
    
    
    public function index() {        
        $cats = Doctrine::getInstance()->getRepository('Entities\Category')->getRootCatAndChildren(self::DEFAULT_LANGUAGE);
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
        $category = $this->input->get('category');
        if(!$category || !is_numeric($category)){
            return;
        }
        
        $cats = Doctrine::getInstance()->getRepository('Entities\Category')->getChildren($category);
        $return = array('next' => false, 'data' => array());
        foreach($cats AS $category){
            $return['next'] = true;
            $return['data'][] = array('id' => $category->getId(), 'image' => base_url('images/'.$category->getId().'.jpg'), 'title' => $category->getName());
        }
        if(!$return['next']){
            $type = Doctrine::getInstance()->getRepository('Entities\Category')->getType($category);
            switch($type){
                case self::TYPE_SLIDER:
                case self::TYPE_SCHEME:
                    $return['data']['type'] = $type;
                    $return['data']['resources'] = array();
                    
                    $oldid = 0;
                    $newid = 0;
                    $tmp = Doctrine::getInstance()->getRepository('Entities\Resource')->getResources($category);
                    foreach($tmp as $key => $val){
                        if($val->getParent() == null){
                            $return['data']['resources'][] = $val->getArray();
                            $newid = $val->getId();
                            unset($tmp[$key]);
                            break;
                        }
                    }
                    while($oldid != $newid && count($tmp) != 0){
                        foreach($tmp as $key => $val){
                            if($val->getParent() == $newid){
                                $return['data']['resources'][] = $val->getArray();
                                $oldid = $newid;
                                $newid = $val->getId();
                                unset($tmp[$key]);
                                break;
                            }
                        }
                    }
                break;
            }
        }
        echo json_encode($return);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
