<?php
namespace Nilemin\Virtualmin\Managers;

use Nilemin\Virtualmin\Http\HttpClientInterface;
use Nilet\Components\Configuration\Config;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
abstract class BaseManager {

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * @var Config
     */
    protected $config;

    public function __construct(HttpClientInterface $httpClient, Config $config = null) {
        $this->httpClient = $httpClient;
        $this->config = $config;
    }
}
