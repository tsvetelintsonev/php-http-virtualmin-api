<?php
namespace Nilemin\Virtualmin\Managers\Server;

use Nilemin\Virtualmin\Entities\Server;
use Nilemin\Virtualmin\Entities\SubServer;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
interface VirtualServerManagerInterface {
    /**
     * Changes virtual server (domain) name
     *
     * @param string $domain
     * @param string $newDomain
     * @return bool TRUE on success, FALSE otherwise
     */
    public function changeServerName(string $domain, string $newDomain) : bool;

    /**
     * Changes virtual server admin password.
     *
     * @param string $domain
     * @param string $pass
     * @return bool TRUE on success, FALSE otherwise
     */
    public function changeAdminPassword(string $domain, string $pass) : bool;

    /**
     * Creates new Virtual Sub Server.
     *
     * @param string $domain Name of the new sub server.
     * @param string $parentDomain Name of the parent server.
     * @param string $description Sub server description.
     * @param array $options Array containing additional domain options e.g mail, ssl, mysql, spam, virus, status, plan, template etc.
     * For full list of the available options visit ({@link https://www.virtualmin.com/documentation/developer/cli/create_domain})
     * Note! "dir" and "web" options are added by default.
     * @return bool TRUE on success, FALSE otherwise
     */
    public function createSubServer(string $domain, string $parentDomain, string $description, array $options = []) : bool;

    
    /**
     * Create a new Virtual Server.
     * 
     * @param string $domain The new domain name.
     * @param string $password The user's password.
     * @param string $description The server's description
     * @param array $options Additional options for the server's creation, expressed in an array.
     * @return bool True on completion, false on error
     * 
     */
    public function createServer(string $domain, string $password, string $description, array $options = []) : bool;
    
    /**
     * Creates Virtual Server alias.
     *
     * @param string $domain Name of the new alias server.
     * @param string $alias Name of the target server.
     * @param string $description Sub server description.
     * @return bool TRUE on success, FALSE otherwise
     */
    public function createServerAlias(string $domain, string $alias, string $description) : bool;

    /**
     * Deletes a given sub server.
     *
     * @param $domain Domain name.
     * @return bool TRUE on success, FALSE otherwise
     */
    public function deleteServer($domain) : bool;

    /**
     * Retrieving single virtual server.
     *
     * @param string $domain
     * @param string $type ServerTypes::TOP_LEVEL_SERVER or ServerTypes::SUB_SERVER
     * @return Server|SubServer
     * @throws UnknownVirtualServerTypeException
     */
    public function fetchServer(string $domain, string $type);

    /**
     * Retrieves all sub servers (addon domains) owned by a given user.
     *
     * @param string $user
     * @return array Array containing SubServer instances.
     */
    public function fetchAddonServers(string $user) : array;

    /**
     * Retrieves all sub servers (sub domains) owned by a given user.
     * They should not be mistaken with entries created with the sub domain option in Virtualmin.
     * These are just ordinary sub servers with prefixed name
     * that is the same with the accounts primary domain name.
     *
     * @param string $user
     * @return array Array containing SubServer instances.
     */
    public function fetchSubServers(string $user) : array;

    /**
     * Retrieves all sub servers (sub domains) names owned by a given user.
     * They should not be mistaken with entries created with the sub domain option in Virtualmin.
     * These are just ordinary sub servers with prefixed name
     * that is the same with the accounts primary domain name.
     *
     * @param string $domain
     * @param string $user
     *
     * @return array
     */
    public function fetchSubServersNames(string $domain, string $user);

    /**
     * Retrieves all sub servers (addon domains) names owned by a given user.
     * They should not be mistaken with entries created with the sub domain option in Virtualmin.
     * These are just ordinary sub servers with prefixed name
     * that is the same with the accounts primary domain name.
     *
     * @param string $domain
     * @param string $user
     * @return array
     */
    public function fetchAddonServersNames(string $domain, string $user);

    /**
     * Retrieves all sub servers (alias domains) owned by a given user.
     *
     * @param string $user
     * @return array Array containing SubServer instances.
     */
    public function fetchAliasServers(string $user) : array;

    /**
     * Adds a Virtual server redirect records.
     *
     * @param string $domain Domain name.
     * @param string $path Domain path to redirect from.
     * @param string $destination Redirect's destination.
     * @return bool
     */
    public function addServerRedirect(string $domain, string $path, string $destination) : bool;

    /**
     * Deletes a Virtual server redirect record.
     *
     * @param string $domain Domain name.
     * @param $path $redirect Redirect path.
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function deleteServerRedirect(string $domain, string $path) : bool;

    /**
     * Retrieves all redirects belonging to a given domain.
     *
     * @param string $domain Domain name.
     * @return array Array containing ServerRedirect instances.
     */
    public function fetchServerRedirects(string $domain) : array;
}
