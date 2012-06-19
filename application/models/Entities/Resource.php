<?php

namespace Entities;

use Doctrine\Mapping;

/**
 * @Entity(repositoryClass="Repositories\ResourceRepository")
 * @Table(name="Resources")
 */
class Resource {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="integer")
     * @ManyToOne(targetEntity="Entities\Category")
     */
    protected $category;

    /**
     * @Column(type="integer")
     */
    protected $type;
    
    /**
     * @ManyToOne(targetEntity="Entities\Resource")
     * @Column(name="parent")
     */
    protected $parent;
    
    /**
     * @Column(type="text")
     */
    protected $value;
    
    /**
     * @Column(type="string",length=100)
     */
    protected $title;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Get category
     *
     * @return Entities\Category 
     */
    public function getCategory() {
        return $this->category;
    }
    
    /**
     * Set category
     *
     * @param Entities\Category $category
     */
    public function setCategory($category) {
        $this->category = $category;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getParent() {
        return $this->parent;
    }

    public function setParent($parent) {
        $this->parent = $parent;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
    }
    
    public function getTitle() {
        return $this->title;
    }
    public function setTitle($title) {
        $this->title = $title;
    }

        
    
    
    public function getArray(){
        return array('value' => $this->getValue(), 'type' => $this->getType());
    }


}