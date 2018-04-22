<?php
namespace Nilemin\Virtualmin\Managers\Cron;

use Nilemin\Virtualmin\Managers\BaseManager;
use Nilemin\Virtualmin\Http\HttpClientInterface;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class CronManager extends BaseManager implements CronManagerInterface {

    /**
     * CronManager constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClientInterface $httpClient) {
        parent::__construct($httpClient);
    }

}
