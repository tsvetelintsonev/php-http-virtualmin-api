<?php
namespace Nilemin\Virtualmin\Managers\PHP;

use Nilemin\Virtualmin\Http\HttpClientInterface;
use Nilemin\Manager\BaseManager;
use Nilemin\Virtualmin\Entities\PHPDirectory;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class PHPManager extends BaseManager implements PHPManagerInterface, PHPManagerInterface {

    public function __construct(HttpClientInterface $httpClient) {
        parent::__construct($httpClient);
    }

    /**
     * Retrieves all directories in which a specific version of PHP has been activated.
     *
     * @param string $domain
     * @return array Array of PHPDirectory instances.
     */
    public function fetchPHPDirectories(string $domain) : array {
        $queryBuilder = $this->httpClient->queryStringBuilder();
        $queryBuilder->addParameter("program", "list-php-directories");
        $queryBuilder->addParameter("domain", $domain);
        $queryBuilder->addParameter("multiline");

        $this->httpClient->sendRequest();
        $data = $this->httpClient->getResponseMessage()->data;
        $directories = [];
        foreach ($data as $directoryInfo) {
            $directories[] = new PHPDirectory($directoryInfo);
        }
        return $directories;
    }

    /**
     * Retrieves all PHP installed versions.
     *
     * @return array Array of \stdClass
     */
    public function fetchInstalledPHPVersions() : array {
        $queryBuilder = $this->httpClient->queryStringBuilder();
        $queryBuilder->addParameter("program", "list-php-versions");
        $queryBuilder->addParameter("name-only");

        $this->httpClient->sendRequest();

        return $this->httpClient->getResponseMessage()->data;
    }

    /**
     * Sets the version of PHP to run in given directory.
     *
     * @param string $domain
     * @param string $directory Relative path to website root directory.
     * @param string $phpVersion
     * @return bool
     */
    public function addPHPDirectory(string $domain, string $directory, string $phpVersion) : bool {
        $queryBuilder = $this->httpClient->queryStringBuilder();
        $queryBuilder->addParameter("program", "set-php-directory");
        $queryBuilder->addParameter("domain", $domain);
        $queryBuilder->addParameter("dir", $directory);
        $queryBuilder->addParameter("version", $phpVersion);

        return $this->httpClient->sendRequest();
    }

    /**
     * Remove any custom version of PHP for a given directory.
     *
     * @param string $domain
     * @param string $directory Relative path to website root directory.
     * @return bool
     */
    public function deletePHPDirectory(string $domain, string $directory) {
        $queryBuilder = $this->httpClient->queryStringBuilder();
        $queryBuilder->addParameter("program", "delete-php-directory");
        $queryBuilder->addParameter("domain", $domain);
        $queryBuilder->addParameter("dir", $directory);

        return $this->httpClient->sendRequest();
    }

    /**
     * Retrieves all PHP variables for a given domain.
     *
     * @param string $domain Domain name.
     * @param string $phpVersion PHP version.
     * @param array $phpIniVars PHP variable names.
     * @return array
     */
    public function fetchPHPIniVars(string $domain, string $phpVersion, array $phpIniVars) : array {
        $queryBuilder = $this->httpClient->queryStringBuilder();
        $queryBuilder->addParameter("program", "list-php-ini");
        $queryBuilder->addParameter("domain", $domain);
        $queryBuilder->addParameter("php-version", $phpVersion);
        foreach ($phpIniVars as $iniVar) {
            $queryBuilder->addParameter("ini-name", $iniVar);
        }
        $this->httpClient->sendRequest();
        // Parse the ugly response from Virtualmin ...
        $data = get_object_vars($this->httpClient->getResponseMessage()->data[0]->values);
        $phpVars = [];
        foreach ($data as $key => $value) {
            $phpVars[$key] = $value[0];
        }
        return $phpVars;
    }

    /**
     * Modifies PHP variables for a given domain.
     *
     * @param string $domain
     * @param string $phpVersion
     * @param array $phpIniVars
     * @return bool
     */
    public function modifyPHPIniVars(string $domain, string $phpVersion, array $phpIniVars) : bool {
        $queryBuilder = $this->httpClient->queryStringBuilder();
        $queryBuilder->addParameter("program", "modify-php-ini");
        $queryBuilder->addParameter("domain", $domain);
        $queryBuilder->addParameter("php-version", $phpVersion);
        foreach ($phpIniVars as $key => $value) {
            $queryBuilder->addParameter("ini-name", $key);
            $queryBuilder->addParameter("ini-value", $value);
        }
        return $this->httpClient->sendRequest();
    }

}
