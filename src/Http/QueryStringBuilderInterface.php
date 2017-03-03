<?php
namespace Nilemin\Virtualmin\Http;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
interface QueryStringBuilderInterface {

    /**
     * @param string $name
     * @param string $value
     *
     * @return mixed
     */
    public function addParameter(string $name, string $value = "");

    /**
     * Flushes the query string.
     *
     * @return string
     */
    public function createQueryString() : string;
}
