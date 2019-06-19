<?php
namespace Nilemin\Virtualmin\Http;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class HttpClient implements HttpClientInterface {

    /**
     * Virtualmin port
     * @var int
     */
    private $port;

    /**
     * Root user name.
     * @var string
     */
    private $rootName;

    /**
     * Root user password.
     * @var string
     */
    private $rootPassword;

    /**
     * @var string
     */
    private $virtualminRemoteApiPath = "virtual-server/remote.cgi";

    /**
     * @var string
     */
    private $cloudminRemoteApi = "server-manager/remote.cgi";

    /**
     * Virtualmin url.
     * @var string
     */
    private $url;

    /**
     * Guzzle HTTP Client.
     * @var \GuzzleHttp\Client
     */
    private $client = null;

    /**
     * The response returned from Guzzle.
     * @var \Psr\Http\Message\ResponseInterface
     */
    private $response;

    /**
     * The response message type - json, xml
     * @var string
     */
    private $responseType = "json";

    /**
     * Response message returned by Virtualmin.
     * @var \stdClass
     */
    private $responseMessage;

    /**
     * The query builder.
     * @var QueryStringBuilder
     */
    private $queryStringBuilder;

    /**
     * HttpClient constructor.
     * @param string $url Virualmins URL.
     * @param int $port Virualmins port.
     * @param string $rootName Root user name.
     * @param string $rootPassword Root user password.
     * @param string $responseType Response format - json, xml.
     */
    public function __construct(string $url, int $port, string $rootName, string $rootPassword, string $responseType) {
        $this->client = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
        $this->queryStringBuilder = new QueryStringBuilder();
        $this->rootName = $rootName;
        $this->rootPassword = $rootPassword;
        $this->port = $port;
        $this->url = "https://{$rootName}:{$rootPassword}@{$url}:{$this->port}/{$this->virtualminRemoteApiPath}";
        $this->responseType = $responseType;
    }

    /**
     * Sends request to Virtualmin's remote api.
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function sendRequest() : bool {
        $this->dispatch();
        return $this->processResponse();
    }

    private function buildUrl() : string {
        return $this->url . "?" .  $this->queryStringBuilder->createQueryString() . "&" . $this->responseType . "=1";
    }

    private function dispatch() {
        $url = $this->buildUrl();
        $this->response = $this->client->get($url);
    }

    private function processResponse() {
        $this->responseMessage = json_decode($this->response->getBody()->getContents());

        if ($this->responseMessage->status == "success") 
            return true;
        
        return false;
    }

    /**
     * Retrieves the response message.
     * @return \stdClass
     */
    public function getResponseMessage() {
        return $this->responseMessage;
    }

    /**
     * @return QueryStringBuilder
     */
    public function queryStringBuilder() : QueryStringBuilder {
        return $this->queryStringBuilder;
    }
}
