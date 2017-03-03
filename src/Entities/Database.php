<?php
namespace Nilemin\Virtualmin\Entities;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class Database extends Entity {

    public function __construct(\stdClass $object) {
        parent::__construct($object);
    }

    public function getTables() : array {
        return $this->object->tables;
    }

    public function getByteSize() : string {
        return $this->object->byte_size[0];
    }

    public function getType() : string {
        return $this->object->type[0];
    }

    public function getSize() : string {
        return $this->object->size[0];
    }
}
