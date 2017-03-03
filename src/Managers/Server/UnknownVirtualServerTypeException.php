<?php
namespace Nilemin\Virtualmin\Managers\Server;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class UnknownVirtualServerTypeException extends \Exception {

    public function __construct($message) {
        parent::__construct($message);
    }

}
