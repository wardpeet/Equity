<?php

namespace Repositories;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository {

    public function getChildren($id) {
        $qb = $this->_em->createQuery("SELECT c FROM Entities\Category c WHERE c.parent = :parent")->setParameter('parent', $id);
        return $qb->getResult();
    }
    
    /**
     * PAY ATENTION, returns a partial object!
     * @param int $language
     * @return array 
     */
    public function getRootCatAndChildren($language) {
        $qb = $this->_em->createQuery("SELECT partial c.{id,name,parent} FROM Entities\Category c WHERE (c.parent IS NULL AND c.language = :lang) OR c.parent = (SELECT c2.id FROM Entities\Category c2 WHERE c2.parent IS NULL AND c2.language = :lang)")->setParameter('lang', $language);
        return $qb->getResult();
    }

}