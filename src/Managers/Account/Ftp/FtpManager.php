<?php
namespace Nilemin\Virtualmin\Managers\Account\Ftp;

use Nilemin\Virtualmin\Http\HttpClientInterface;
use Nilemin\Virtualmin\Entities\Account;
use Nilemin\Virtualmin\Managers\Account\AccountModifier;
use Nilemin\Virtualmin\Managers\BaseManager;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class FtpManager extends BaseManager implements FtpManagerInterface {

    /**
     * FtpAccountManager constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClientInterface $httpClient) {
        parent::__construct($httpClient);
    }

    /**
     * Creates ftp account.
     *
     * @param string   $domain
     * @param string $username
     * @param string $password
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function createFtpAccount(string $domain, string $username, string $password) : bool {
        $this->httpClient->queryStringBuilder()->addParameter("program", "create-user");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("user", $username);
        $this->httpClient->queryStringBuilder()->addParameter("pass", $password);
        $this->httpClient->queryStringBuilder()->addParameter("web");
        $this->httpClient->queryStringBuilder()->addParameter("ftp");
        $this->httpClient->queryStringBuilder()->addParameter("noemail");

        return $this->httpClient->sendRequest();
    }

    /**
     * Deletes ftp account.
     *
     * @param string $domain
     * @param string $username
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function deleteFtpAccount(string $domain, string $username) : bool {
        $handler = new AccountModifier($this->httpClient,$domain, $username);
        return $handler->deleteAccount($domain, $username);
    }

    /**
     * Disables ftp account.
     *
     * @param string $domain
     * @param string $username
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function disableFtpAccount(string $domain, string $username) : bool {
        $handler = new AccountModifier($this->httpClient, $domain, $username);
        return $handler->disableAccount($domain, $username, "ftp");
    }

    /**
     * Enables ftp account.
     *
     * @param string $domain
     * @param string $username
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function enableFtpAccount(string $domain, string $username) : bool {
        $handler = new AccountModifier($this->httpClient, $domain, $username);
        return $handler->enableAccount($domain, $username, "ftp");
    }

    /**
     * Retrieves all ftp accounts of given domain.
     *
     * @param $domain
     * @return array Array containing \stdClass instances.
     */
    public function fetchFtpAccounts($domain) : array {
        $this->httpClient->queryStringBuilder()->addParameter("program", "list-users");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("multiline");

        $this->httpClient->sendRequest();

        return $this->filterFtpAccount($this->httpClient->getResponseMessage()->data);
    }

    private function filterFtpAccount(array $accounts) {
        $ftpAccounts = [];
        foreach ($accounts as $account) {
            if($account->values->ftp_access[0] === "Yes") {
                $ftpAccounts[] = new Account($account);
            }
        }
        return $ftpAccounts;
    }
}
