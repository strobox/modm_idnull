<?php

namespace App\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Event\PreLoadEventArgs;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;

/**
 * @HasLifecycleCallbacks
 * @MongoDB\MappedSuperclass(repositoryClass="App\Repository\LinkNodeRepository")
 * @MongoDB\InheritanceType("COLLECTION_PER_CLASS")
 * @MongoDB\DiscriminatorField("nodeType")
 * @MongoDB\DiscriminatorMap({
 *     "folder"=App\Document\Folder::class,
 *     "customer"=App\Document\Customer::class
 *  })
 * @MongoDB\DefaultDiscriminatorValue("folder")
 */
class LinkNode
{

    /** @MongoDB\PreLoad */
    public function preLoad(PreLoadEventArgs $eventArgs): void
    {
        $this->initNodeType();
    }
    public function __construct(string $name = "")
    {
        $this->nodeName = $name;
        $this->items = new ArrayCollection();
        $this->initNodeType();
    }

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string", nullable=true)
     */
    protected $name;

    /**
     * @return mixed
     */
    public function getItemOf()
    {
        return $this->itemOf;
    }

    /**
     * @param mixed $itemOf
     */
    public function setItemOf($itemOf): void
    {
        $this->itemOf = $itemOf;
    }

    /**
     * @MongoDB\ReferenceOne(targetDocument="App\Document\LinkNode") // , inversedBy="items"
     */
    protected $itemOf;

    /**
     * @MongoDB\ReferenceMany(targetDocument="App\Document\LinkNode", mappedBy="itemOf",repositoryMethod="findItemsOfNode")
     */
    private $items = [];

    protected $nodeType;

    /**
     * @return mixed
     */
    public function getNodeType()
    {
        return $this->nodeType;
    }

    /**
     * @param mixed $nodeType
     */
    public function setNodeType(string $nodeType = null): void
    {
        $this->nodeType = $nodeType;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items): void
    {
        $this->items = $items;
    }

    public function addItem(LinkNode $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setItemOf($this);
        }

        return $this;
    }

    public function removeItem(LinkNode $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            $item->setItemOf(null);
        }

        return $this;
    }

    /**/
    public const DESCR_MAP = [
        'folder' => Folder::class,
        'customer' => Customer::class
    ];

    private function initNodeType()
    {
        $this->nodeType = array_search(get_class($this),self::DESCR_MAP);

    }

}
