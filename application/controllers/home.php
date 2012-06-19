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

    public function next($category=0) {
        if(!$category || !is_numeric($category)){
            echo json_encode(array());
        }
        $category = (int) $category;

        $cats = Doctrine::getInstance()->getRepository('Entities\Category')->getChildren($category);
        $return = array('next' => false, 'data' => array());
        foreach($cats as $category){
            $return['next'] = true;
            $return['data'][] = array('id' => $category->getId(), 'image' => base_url('images/' . $category->getId() . '.jpg'), 'title' => $category->getName());
        }

        if(!$return['next']){
            $type = Doctrine::getInstance()->getRepository('Entities\Category')->getType($category);

            switch($type){
                case self::TYPE_SLIDER:
                case self::TYPE_SCHEME:
                    $return['data']['type'] = $type;
                    $return['data']['resources'] = $return['data']['choices'] = array();

                    $resources = Doctrine::getInstance()->getRepository('Entities\Resource')->getResources($category);

                    $parent = -1;
                    $choices = array();
                    $resource = reset($resources);
                    // set data en get diff choices
                    while($resource) {
                        if ($type == self::TYPE_SLIDER) {
                            if($parent == $resource->getParent()) {
                                if (!$choices) {
                                    $choices[] = prev($resources);
                                    next($resources);
                                }

                                $choices[] = $resource;
                            }

                            $parent = $resource->getParent();
                        }
                        $return['data']['resources'][] = array('type' => $resource->getType(),
                            'value' => str_replace($_SERVER['DOCUMENT_ROOT'], '', $resource->getValue()),
                            'title' => $resource->getTitle());

                        $resource = next($resources);
                    }

                    // now calculate the choices
                    $return['data']['choices'] = array();
                    if ($choices) {
                        foreach($choices as $choice) {
                            $tmpChoices = array('text' => $choice->getTitle(),
                                'resources' => array());

                            $current = $choice->getId();
                            $i=1;
                            foreach ($resources as $resource) {
                                if($current == $resource->getId()) {
                                    $tmpChoices['resources'] = $i;
                                } else if($current == $resource->getParent()) {
                                    $current = $resource->getId();
                                    $tmpChoices['resources'] .= '-' . $i;
                                }
                                ++$i;
                            }

                            $return['data']['choices'][] = $tmpChoices;
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
