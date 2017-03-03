<?php
namespace Nilemin\Virtualmin\Managers\Scripts;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
interface ScriptsManagerInterface {
    /**
     * Installs a third party CMS software under a given domain.
     *
     * @param string $domain Domain name
     * @param array  $sctiptInfo
     *                       "wordpress" => [
     *                       // scripts short name
     *                       "scriptName" => "wordpress",
     *                       "dir" => "wordpress",
     *                       "dbName" => "wordpress"
     *                       ]
     *
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function installCMS(string $domain, array $sctiptInfo) : bool;

    /**
     * Installs PHPMyAdmin.
     *
     * @param string $domain Domain name.
     * @param array  $sctiptInfo
     *                       "phpmyadmin" => [
     *                       "scriptName" => "phpmyadmin",
     *                       "dir" => "rdbms"
     *                       ]
     *
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function installPHPMyAdmin(string $domain, array $sctiptInfo) : bool;

    /**
     * Deletes/uninstalls a given script.
     *
     * @param string $domain Domain name.
     * @param string $type   Scripts short name.
     *
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function deleteScript(string $domain, string $type) : bool;

    public function fetchInstalledScripts(string $domain);

    public function fetchInstalledScript(string $domain, string $type);
}
