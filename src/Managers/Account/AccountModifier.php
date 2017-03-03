<?php
namespace Nilemin\Virtualmin\Managers\Account;

use Nilemin\Virtualmin\Http\HttpClient;

/*
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class AccountModifier {

    public function deleteAccount(HttpClient $httpClient, string $domain, string $username) : bool {
        $httpClient->queryStringBuilder()->addParameter("program", "delete-user");
        $httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $httpClient->queryStringBuilder()->addParameter("user", $username);

        return $httpClient->sendRequest();
    }

    public function disableAccount(HttpClient $httpClient, string $domain, string $username, string $accountType) : bool {
        $httpClient->queryStringBuilder()->addParameter("program", "modify-user");
        $httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $httpClient->queryStringBuilder()->addParameter("user", $username);
        $httpClient->queryStringBuilder()->addParameter("disable-{$accountType}");

        return $httpClient->sendRequest();
    }

    public function enableAccount(HttpClient $httpClient, string $domain, string $username, string $accountType) : bool {
        $httpClient->queryStringBuilder()->addParameter("program", "modify-user");
        $httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $httpClient->queryStringBuilder()->addParameter("user", $username);
        $httpClient->queryStringBuilder()->addParameter("enable-{$accountType}");

        return $httpClient->sendRequest();
    }

}
