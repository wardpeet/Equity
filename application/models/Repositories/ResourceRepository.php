<?php

namespace Repositories;

use Doctrine\ORM\EntityRepository;

class ResourceRepository extends EntityRepository {

    public function getResource($id) {
        $qb = $this->_em->createQuery("SELECT r FROM Entities\Resource r WHERE r.id = :id")->setParameter('id', $id);
        return $qb->getResult();
    }
    
    public function getResources($category_id) {
        $qb = $this->_em->createQuery("SELECT r FROM Entities\Resource r WHERE r.category = :cat")->setParameter('cat', $category_id);
        return $qb->getResult();
    }

}