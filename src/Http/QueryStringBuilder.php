<?php
namespace Nilemin\Virtualmin\Http;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class QueryStringBuilder implements QueryStringBuilderInterface {

    private $queryString;

    public function addParameter(string $name, string $value = "") {
        $this->queryString .= $name . "=" . $value . "&";
    }

    /**
     * Flushes the query string.
     * @return string
     */
    public function createQueryString(): string {
        $string = rtrim($this->queryString, "&");
        $this->queryString = "";
        return $string;
    }

}
