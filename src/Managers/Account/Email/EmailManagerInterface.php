<?php
namespace Nilemin\Virtualmin\Managers\Account\Email;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
interface EmailManagerInterface {
    /**
     * Creates email account.
     *
     * @param string $domain
     * @param string $username
     * @param string $password
     * @param string $realname
     * @param int|null $quota Disk quota for the email account in MB.
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function createEmailAccount(string $domain, string $username, string $password, string $realname, int $quota = null) : bool;

    /**
     * Changes email account quota.
     *
     * @param string $domain
     * @param string $username
     * @param int $quota
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function changeEmailAccountQuota(string $domain, string $username, int $quota) : bool;

    /**
     * Deletes email account.
     *
     * @param string $domain
     * @param string $username
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function deleteEmailAccount(string $domain, string $username) : bool;

    /**
     * Disables email account.
     *
     * @param string $domain
     * @param string $username
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function disableEmailAccount(string $domain, string $username) : bool;

    /**
     * Enables email account.
     *
     * @param string $domain
     * @param string $username
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function enableEmailAccount(string $domain, string $username) : bool;

    /**
     * Retrieves all email accounts of given domain.
     *
     * @param $domain
     * @return array Array containing \stdClass instances.
     */
    public function fetchEmailAccounts($domain);
}
