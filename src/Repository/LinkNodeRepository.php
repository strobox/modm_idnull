<?php

namespace App\Repository;

use App\Document\Customer;
use App\Document\Folder;
use App\Document\LinkNode;
use Doctrine\ODM\MongoDB\Iterator\CachingIterator;
use Doctrine\ODM\MongoDB\Iterator\Iterator;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use MongoDB\BSON\ObjectId;

class LinkNodeRepository extends DocumentRepository
{

    /**
     * @param LinkNode $linkNode
     * @return Iterator|null
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @throws \Exception
     */
    public function findItemsOfNode(LinkNode $linkNode): ?Iterator
    {
//        if(get_class($linkNode)==Folder::class) {
            $res = [];
            foreach ($this->class->discriminatorMap as $discr => $clazz) {

                $qiter = $this->getItemsOfType($linkNode->getId(), $clazz);

                $q_arr = $qiter->toArray();
                if(count($q_arr)>0) {
                    if(get_class($linkNode)==Folder::class && $clazz==Folder::class) {/* Folder should not */
                        throw new \Exception("Conceptually I against of storing folders as item of folder");
                        // because we loose hierarchy
                    }
                    $res = array_merge($res,$q_arr);
                }

            }
            $iter = new CachingIterator( new \ArrayIterator($res));
//        } else {
//            $iter = new CachingIterator(new EmptyIterator());
//        }


        return $iter;
    }



    /**
     * @return LinkNode[] Returns an array of LinkNode objects
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     */
    public function getNodeItems($nodeId,$nodeType,$itemsType="all")
    {
        /* @var LinkNode[] $res */
        if(array_key_exists($itemsType,LinkNode::DESCR_MAP)) {
            $res = $this->getItemsOfType($nodeId,LinkNode::DESCR_MAP[$itemsType])->toArray();
        } else {
            $initalDocName = $this->documentName;
            $this->documentName = LinkNode::DESCR_MAP[$nodeType];
            /** @var LinkNode $node */
            $node = $this->find($nodeId);
            $res = $node->getItems()->toArray();
            $this->documentName = $initalDocName;

        }
        return $res;

    }

    /**
     * @param string $linkNode
     * @param $clazz
     * @return array|Iterator|int|\MongoDB\DeleteResult|\MongoDB\InsertOneResult|\MongoDB\UpdateResult|null|object
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getItemsOfType(string $id, $clazz)
    {
        $initalDocName = $this->documentName;
        $this->documentName = $clazz;
        $qiter = $this->createQueryBuilder()
            ->field('itemOf.$id')->equals(new ObjectId($id))
            //->field('itemOf.nodeType')->equals($discr)
            ->getQuery()->execute();
        $this->documentName = $initalDocName;
        return $qiter;
    }

}
