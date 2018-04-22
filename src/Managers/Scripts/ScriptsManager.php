<?php
namespace Nilemin\Virtualmin\Managers\Scripts;

use Nilemin\Virtualmin\Http\HttpClientInterface;
use Nilemin\Virtualmin\Managers\BaseManager;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class ScriptsManager extends BaseManager implements ScriptsManagerInterface {

    /**
     * ScriptsManager constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClientInterface $httpClient) {
        parent::__construct($httpClient);
    }

    /**
     * Installs a third party CMS software under a given domain.
     *
     * @param string $domain Domain name
     * @param array $sctiptInfo
     * "wordpress" => [
     *      // scripts short name
     *       "scriptName" => "wordpress",
     *       "dir" => "wordpress",
     *       "dbName" => "wordpress"
     *   ]
     *
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function installCMS(string $domain, array $sctiptInfo) : bool {
        $queryBuilder = $this->httpClient->queryStringBuilder();
        $queryBuilder->addParameter("program", "install-script");
        $queryBuilder->addParameter("domain", $domain);
        $queryBuilder->addParameter("type", $sctiptInfo["scriptName"]);
        $queryBuilder->addParameter("version", "latest");
        $queryBuilder->addParameter("path", "/".trim($sctiptInfo["dir"], "/"));
        $queryBuilder->addParameter("newdb");
        $queryBuilder->addParameter("db", "mysql {$sctiptInfo["dbName"]}");

        return $this->httpClient->sendRequest();
    }

    /**
     * Installs PHPMyAdmin.
     *
     * @param string $domain Domain name.
     * @param array $sctiptInfo
     * "phpmyadmin" => [
     *       "scriptName" => "phpmyadmin",
     *       "dir" => "rdbms"
     *   ]
     *
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function installPHPMyAdmin(string $domain, array $sctiptInfo) : bool {
        $queryBuilder = $this->httpClient->queryStringBuilder();
        $queryBuilder->addParameter("program", "install-script");
        $queryBuilder->addParameter("domain", $domain);
        $queryBuilder->addParameter("type", $sctiptInfo["scriptName"]);
        $queryBuilder->addParameter("version", "latest");
        $queryBuilder->addParameter("path", "/".trim($sctiptInfo["dir"], "/"));

        return $this->httpClient->sendRequest();
    }

    /**
     * Deletes/uninstalls a given script.
     *
     * @param string $domain Domain name.
     * @param string $type Scripts short name.
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function deleteScript(string $domain, string $type) : bool {
        $queryBuilder = $this->httpClient->queryStringBuilder();
        $queryBuilder->addParameter("program", "delete-script");
        $queryBuilder->addParameter("domain", $domain);
        $queryBuilder->addParameter("type", $type);

        return $this->httpClient->sendRequest();
    }

    public function fetchInstalledScripts(string $domain) {
        $queryBuilder = $this->httpClient->queryStringBuilder();
        $queryBuilder->addParameter("program", "list-scripts");
        $queryBuilder->addParameter("domain", $domain);
        $queryBuilder->addParameter("multiline");

        $this->httpClient->sendRequest();
        $data = $this->httpClient->getResponseMessage()->data;
        $scripts = [];
        foreach ($data as $item) {
            $scriptData = get_object_vars($item->values);
            foreach ($scriptData as $key => $value) {
                $scriptData[$key] = $value[0];
            }
            $scripts[] = $scriptData;
        }
        return $scripts;
     }

    public function fetchInstalledScript(string $domain, string $type) {
        $queryBuilder = $this->httpClient->queryStringBuilder();
        $queryBuilder->addParameter("program", "list-scripts");
        $queryBuilder->addParameter("domain", $domain);
        $queryBuilder->addParameter("type", $type);
        $queryBuilder->addParameter("multiline");

        $this->httpClient->sendRequest();
        $data = get_object_vars($this->httpClient->getResponseMessage()->data[0]->values);
        $scriptData = [];
        foreach ($data as $key => $value) {
            $scriptData[$key] = $value[0];
        }
        return $scriptData;
    }

}
