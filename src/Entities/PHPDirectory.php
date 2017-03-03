<?php
namespace Nilemin\Virtualmin\Entities;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class PHPDirectory extends Entity {

    public function __construct(\stdClass $object) {
        parent::__construct($object);
    }

    public function isWebRootDirectory() {
        return $this->object->web_root_directory[0] === "YES";
    }

    public function getFullPHPVersion() {
        return $this->object->full_version[0];
    }

    public function getPHPVersion() {
        return $this->object->php_version[0];
    }

    public function getPHPExecutionMode() {
        return $this->object->execution_mode[0];
    }

}
