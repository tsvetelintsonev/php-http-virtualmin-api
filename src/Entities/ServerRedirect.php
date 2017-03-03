<?php
namespace Nilemin\Virtualmin\Entities;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class ServerRedirect extends Entity {

    public function __construct(\stdClass $object) {
        parent::__construct($object);
    }

    public function getDestination() {
        return $this->object->destination[0];
    }

}
