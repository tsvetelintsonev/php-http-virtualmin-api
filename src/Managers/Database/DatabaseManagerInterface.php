<?php

namespace Nilemin\Virtualmin\Managers\Database;


/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
interface DatabaseManagerInterface {
    /**
     * Grants database access to user.
     *
     * @param string $domain
     * @param string $username
     * @param        $database
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function grantDatabaseAccess(string $domain, string $username, $database) : bool;

    /**
     * Removes database user access.
     *
     * @param string $domain
     * @param string $username
     * @param        $database
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function removeDatabaseAccess(string $domain, string $username, $database) : bool;

    /**
     * Creates a MySQL database for a given virtual server.
     *
     * @param string $domain
     * @param string $databaseName
     *
     * @return bool
     */
    public function createDatabase(string $domain, string $databaseName) : bool;

    /**
     * Deletes a MySQL database.
     *
     * @param string $domain
     * @param string $databaseName
     *
     * @return bool
     */
    public function deleteDatabase(string $domain, string $databaseName) : bool;

    /**
     * Retrieves all MySQL databases names for a given domain.
     *
     * @param $domain
     *
     * @return mixed
     */
    public function fetchDatabasesNames($domain);

    /**
     * Retrieves all MySQL databases for a given domain.
     *
     * @param $domain
     *
     * @return mixed
     */
    public function fetchDatabases($domain);
}
