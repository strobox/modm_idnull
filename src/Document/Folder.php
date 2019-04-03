<?php

namespace App\Document;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Xapapi\Model\FolderModel;

/**
 * _@_ApiResource() leads to BUG :
 *      Uncaught Symfony\Component\Debug\Exception\FatalThrowableError: Return value of Doctrine\ODM\MongoDB\Mapping\ClassMetadata::getAssociationTargetClass()
 *      must be of the type string, null returned in
 *      /var/www/symfony/vendor/doctrine/mongodb-odm/lib/Doctrine/ODM/MongoDB/Mapping/ClassMetadata.php:1743"
 * @MongoDB\Document(repositoryClass="App\Repository\FolderRepository")
 */
class Folder extends LinkNode
{

    public function __construct(string $name = "")
    {
        parent::__construct($name);
    }

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="bool")
     */
    protected $public;


    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPublic()
    {
        return $this->public;
    }

    public function setPublic($public): self
    {
        $this->public = $public;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNodeType()
    {
        return $this->nodeType;
    }
}
