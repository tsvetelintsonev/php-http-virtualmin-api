<?php

namespace Nilemin\Virtualmin\Managers\Account\Ftp;


/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
interface FtpManagerInterface {
    /**
     * Creates ftp account.
     *
     * @param string $domain
     * @param string $username
     * @param string $password
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function createFtpAccount(string $domain, string $username, string $password) : bool;

    /**
     * Deletes ftp account.
     *
     * @param string $domain
     * @param string $username
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function deleteFtpAccount(string $domain, string $username) : bool;

    /**
     * Disables ftp account.
     *
     * @param string $domain
     * @param string $username
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function disableFtpAccount(string $domain, string $username) : bool;

    /**
     * Enables ftp account.
     *
     * @param string $domain
     * @param string $username
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function enableFtpAccount(string $domain, string $username) : bool;

    /**
     * Retrieves all ftp accounts of given domain.
     *
     * @param $domain
     *
     * @return array Array containing \stdClass instances.
     */
    public function fetchFtpAccounts($domain) : array;
}
