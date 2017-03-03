<?php
namespace Nilemin\Virtualmin\Entities;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class Entity {

    /**
     * @var string
     */
    private $name;

    /**
     * @var \stdClass
     */
    protected $object;

    public function __construct(\stdClass $object) {
        $this->name = $object->name;
        $this->object = $object->values;
    }

    public function getName() : string {
        return $this->object->name;
    }

}
