<?php

namespace Entities;

use Doctrine\Mapping;

/**
 * @Entity(repositoryClass="Repositories\CategoryRepository")
 * @Table(name="categories")
 */
class Category {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="string",length=50)
     */
    protected $name;

    /**
     * @Column(type="integer")
     */
    protected $type;

    /**
     * @Column(type="string",length=255)
     */
    protected $tags;
    
    /**
     * @ManyToOne(targetEntity="Entities\Category")
     * @Column(name="parent")
     */
    protected $parent;
    
    /**
     * @ManyToOne(targetEntity="Entities\Language")
     * @Column(name="language")
     */
    protected $language;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function setType($type) {
       $this->type = $type;
    }

    public function getTags() {
        return $this->tags;
    }

    public function setTags($tags) {
        $this->tags = $tags;
    }

    public function getParent() {
        return $this->parent;
    }

    public function setParent($parent) {
        $this->parent = $parent;
    }
    
     /**
     * Get language
     *
     * @return Entities\Language 
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Set language
     *
     * @param Entities\Language $role
     */
    public function setLanguage(Entities\Language $language = null) {
        $this->language = $language;
    }

}