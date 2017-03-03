<?php
namespace Nilemin\Virtualmin\Http;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
interface HttpClientInterface {
    /**
     * Sends request to Virtualmin's remote api.
     *
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function sendRequest() : bool;

    /**
     * Retrieves the response message.
     *
     * @return \stdClass
     */
    public function getResponseMessage();

    /**
     * @return QueryStringBuilder
     */
    public function queryStringBuilder() : QueryStringBuilder;
}
