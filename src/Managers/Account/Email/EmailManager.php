<?php
namespace Nilemin\Virtualmin\Managers\Account\Email;

use Nilemin\Virtualmin\Http\HttpClient;
use Nilemin\Virtualmin\Entities\Account;
use Nilemin\Virtualmin\Managers\Account\AccountModifier;
use Nilemin\Virtualmin\Managers\BaseManager;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class EmailManager extends BaseManager implements EmailManagerInterface {

    /**
     * EmailAccountManager constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient) {
        parent::__construct($httpClient);
    }

    /**
     * Creates email account.
     *
     * @param string   $domain
     * @param string   $username
     * @param string   $password
     * @param string   $realname
     * @param int|null $quota Disk quota for the email account in MB.
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function createEmailAccount(string $domain, string $username, string $password, string $realname, int $quota = null) : bool {
        $this->httpClient->queryStringBuilder()->addParameter("program", "create-user");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("user", $username);
        $this->httpClient->queryStringBuilder()->addParameter("pass", $password);
        $this->httpClient->queryStringBuilder()->addParameter("real", $realname);
        if ($quota !== null) {
            $quota *= 1000;
            $this->httpClient->queryStringBuilder()->addParameter("quota", $quota);
        }
        return $this->httpClient->sendRequest();
    }

    /**
     * Changes email account quota.
     *
     * @param string $domain
     * @param string $username
     * @param int    $quota
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function changeEmailAccountQuota(string $domain, string $username, int $quota) : bool {
        $this->httpClient->queryStringBuilder()->addParameter("program", "modify-user");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("user", $username);
        $quota *= 1000;
        $this->httpClient->queryStringBuilder()->addParameter("quota", $quota);

        return $this->httpClient->sendRequest();
    }

    /**
     * Deletes email account.
     *
     * @param string $domain
     * @param string $username
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function deleteEmailAccount(string $domain, string $username) : bool {
        $handler = new AccountModifier($this->httpClient,$domain, $username);
        return $handler->deleteAccount($domain, $username);
    }

    /**
     * Disables email account.
     *
     * @param string $domain
     * @param string $username
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function disableEmailAccount(string $domain, string $username) : bool {
        $handler = new AccountModifier($this->httpClient, $domain, $username);
        return $handler->disableAccount($domain, $username, "email");
    }

    /**
     * Enables email account.
     *
     * @param string $domain
     * @param string $username
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function enableEmailAccount(string $domain, string $username) : bool {
        $handler = new AccountModifier($this->httpClient, $domain, $username);
        return $handler->enableAccount($domain, $username, "email");
    }

    /**
     * Retrieves all email accounts of given domain.
     *
     * @param $domain
     * @return array Array containing \stdClass instances.
     */
    public function fetchEmailAccounts($domain) {
        $this->httpClient->queryStringBuilder()->addParameter("program", "list-users");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("multiline");

        $this->httpClient->sendRequest();

        return $this->filterEmailAccounts($this->httpClient->getResponseMessage()->data);
    }

    private function filterEmailAccounts(array $accounts) {
        $emailAccounts = [];
        foreach ($accounts as $account) {
            if ($account->values->login_permissions[0] === "Email only") {
                $emailAccounts[] = new Account($account);
            }
        }
        return $emailAccounts;
    }
}
