<?php

namespace Equinoxe\TestBundle\Test;

class EntityManagerMock
{
    protected $id = 0;
    protected $persisted = array();
    protected $notFlushed = array();
    protected $flushed = array();
    protected $persistCount = 0;
    protected $flushCount = 0;

    public function __construct($em = null)
    {
        $this->em = $em;
    }

    
    public function persist(&$object)
    {
        $this->persistCount++;
        $this->id++;

        $prop = 'uid';
        if ($this->em != null) {
            $metaFactory = $this->em->getMetaDataFactory();
            $meta = $metaFactory->getMetadataFor(get_class($object));
            $prop = $meta->identifier[0];
        }

        $reflection = new \ReflectionProperty(get_class($object), $prop);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $this->id);
        $reflection->setAccessible(false);

        $this->persisted[] = $object;
        $this->notFlushed[] = $object;
    }

    public function isPersisted($object)
    {
        return in_array($object, $this->persisted);
    }

    public function isFlushed($object)
    {
        return in_array($object, $this->flushed);
    }

    public function flush()
    {
        $this->flushCount++;
        foreach($this->notFlushed as $object) {
            $this->flushed[] = $object;
        }
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPersisted()
    {
        return $this->persisted;
    }

    public function setPersisted($persisted)
    {
        $this->persisted = $persisted;
    }

    public function getPersistCount()
    {
        return $this->persistCount;
    }

    public function setPersistCount($persistCount)
    {
        $this->persistCount = $persistCount;
    }

    public function getFlushCount()
    {
        return $this->flushCount;
    }

    public function setFlushCount($flushCount)
    {
        $this->flushCount = $flushCount;
    }

}