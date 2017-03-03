<?php
namespace Nilemin\Virtualmin\Managers\PHP;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
interface PHPManagerInterface {
    /**
     * Retrieves all directories in which a specific version of PHP has been activated.
     *
     * @param string $domain
     *
     * @return array Array of PHPDirectory instances.
     */
    public function fetchPHPDirectories(string $domain) : array;

    /**
     * Retrieves all PHP installed versions.
     *
     * @return array Array of \stdClass
     */
    public function fetchInstalledPHPVersions() : array;

    /**
     * Sets the version of PHP to run in given directory.
     *
     * @param string $domain
     * @param string $directory Relative path to website root directory.
     * @param string $phpVersion
     *
     * @return bool
     */
    public function addPHPDirectory(string $domain, string $directory, string $phpVersion) : bool;

    /**
     * Remove any custom version of PHP for a given directory.
     *
     * @param string $domain
     * @param string $directory Relative path to website root directory.
     *
     * @return bool
     */
    public function deletePHPDirectory(string $domain, string $directory);

    /**
     * Retrieves all PHP variables for a given domain.
     *
     * @param string $domain     Domain name.
     * @param string $phpVersion PHP version.
     * @param array  $phpIniVars PHP variable names.
     *
     * @return array
     */
    public function fetchPHPIniVars(string $domain, string $phpVersion, array $phpIniVars) : array;

    /**
     * Modifies PHP variables for a given domain.
     *
     * @param string $domain
     * @param string $phpVersion
     * @param array  $phpIniVars
     *
     * @return bool
     */
    public function modifyPHPIniVars(string $domain, string $phpVersion, array $phpIniVars) : bool;
}
