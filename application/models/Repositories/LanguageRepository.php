<?php

namespace Repositories;

use Doctrine\ORM\EntityRepository;

class LanguageRepository extends EntityRepository {

    public function getLanguage($id) {
        $qb = $this->createQuery("SELECT name FROM languages
WHERE id = :id")->setParameter('id', $id);
        return $qb->getQuery()->getResult();
    }
    
    public function getAllLanguages(){
        $qb = $this->createQuery("SELECT id, name FROM languages");
        return $qb->getQuery()->getResult();
    }

}