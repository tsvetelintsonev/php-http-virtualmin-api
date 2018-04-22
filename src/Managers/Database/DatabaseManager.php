<?php
namespace Nilemin\Virtualmin\Managers\Database;

use Nilemin\Virtualmin\Http\HttpClientInterface;
use Nilemin\Virtualmin\Managers\BaseManager;
use Nilemin\Virtualmin\Entities\Database;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class DatabaseManager extends BaseManager implements DatabaseManagerInterface {

    /**
     * DatabaseManager constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClientInterface $httpClient) {
        parent::__construct($httpClient);
    }

    /**
     * Grants database access to user.
     *
     * @param string $domain
     * @param string $username
     * @param        $database
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function grantDatabaseAccess(string $domain, string $username, $database) : bool {
        return $this->handleDatabaseAccess($domain, $username, $database, "add");
    }

    /**
     * Removes database user access.
     *
     * @param string $domain
     * @param string $username
     * @param        $database
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function removeDatabaseAccess(string $domain, string $username, $database) : bool {
        return $this->handleDatabaseAccess($domain, $username, $database, "remove");
    }

    private function handleDatabaseAccess(string $domain, string $username, $database, $action) : bool {
        $this->httpClient->queryStringBuilder()->addParameter("program", "modify-user");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("user", $username);
        $this->httpClient->queryStringBuilder()->addParameter("{$action}-mysql", $database);

        return $this->httpClient->sendRequest();
    }

    /**
     * Creates a MySQL database for a given virtual server.
     *
     * @param string $domain
     * @param string $database Database name
     *
     * @return bool TRUE on success, FALSE otherwise
     */
    public function createDatabase(string $domain, string $database
    ) : bool {
        $this->httpClient->queryStringBuilder()->addParameter("program", "create-database");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("name", $database);
        $this->httpClient->queryStringBuilder()->addParameter("type", "mysql");
        $this->httpClient->queryStringBuilder()->addParameter("opt", "charset utf8");

        return $this->httpClient->sendRequest();
    }

    /**
     * Deletes a MySQL database.
     *
     * @param string $domain
     * @param string $database Database name.
     *
     * @return bool
     */
    public function deleteDatabase(string $domain, string $database) : bool {
        $this->httpClient->queryStringBuilder()->addParameter("program", "delete-database");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("name", $database);
        $this->httpClient->queryStringBuilder()->addParameter("type", "mysql");

        return $this->httpClient->sendRequest();
    }

    /**
     * Retrieves all MySQL databases names for a given domain.
     *
     * @param $domain
     *
     * @return mixed
     */
    public function fetchDatabasesNames($domain) {
        $this->httpClient->queryStringBuilder()->addParameter("program", "list-databases");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("type", "mysql");
        $this->httpClient->queryStringBuilder()->addParameter("name-only");

        $this->httpClient->sendRequest();

        $data = $this->httpClient->getResponseMessage()->data;
        $names = [];
        foreach ($data as $item) {
            $names[] = $item->name;
        }
        return $names;
    }

    /**
     * Retrieves all MySQL databases for a given domain.
     *
     * @param $domain
     *
     * @return mixed
     */
    public function fetchDatabases($domain) {
        $this->httpClient->queryStringBuilder()->addParameter("program", "list-databases");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("type", "mysql");
        $this->httpClient->queryStringBuilder()->addParameter("multiline");

        $this->httpClient->sendRequest();

        $data = $this->httpClient->getResponseMessage()->data;
        $databases = [];
        foreach ($data as $db) {
            $databases[] = new Database($db);
        }
        return $databases;
    }
}
