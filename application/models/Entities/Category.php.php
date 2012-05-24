<?php

namespace Entities;
use Doctrine\Mapping;

/**
 * @Entity(repositoryClass="Repositories\CategoryRepository")
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
     * @Column(type="string",length=255)
     */
    protected $tags;
    
    /**
     * @ManyToOne(targetEntity="Entities\Categories")
     */
    protected $parent;
    
    /**
     * @ManyToOne(targetEntity="Entities\Languages")
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
     * @return Category
     */
    public function setLanguage(Entities\Language $language = null) {
        $this->language = $language;
        return $this;
    }

}