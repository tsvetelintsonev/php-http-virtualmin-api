<?php
namespace Nilemin\Virtualmin\Managers\Cron;

use Nilemin\Virtualmin\BaseManager;
use Nilemin\Virtualmin\Http\HttpClient;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class CronManager extends BaseManager implements CronManagerInterface, CronManagerInterface {

    /**
     * CronManager constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient) {
        parent::__construct($httpClient);
    }

}
