<?php

namespace Entities;

use Doctrine\Mapping;

/**
 * @Entity(repositoryClass="Repositories\LanguageRepository")
 * @Table(name="languages")
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
}