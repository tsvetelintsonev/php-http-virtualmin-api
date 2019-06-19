<?php
namespace Nilemin\Virtualmin\Managers\Server;

use Nilemin\Virtualmin\Http\HttpClientInterface;
use Nilemin\Virtualmin\Managers\BaseManager;
use Nilemin\Virtualmin\Entities\Server;
use Nilemin\Virtualmin\Entities\ServerRedirect;
use Nilemin\Virtualmin\Entities\SubServer;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class VirtualServerManager extends BaseManager implements VirtualServerManagerInterface {

    /**
     * VirtualServerManager constructor.
     *
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient) {
        parent::__construct($httpClient);
    }

    /**
     * Changes virtual server (domain) name
     *
     * @param string $domain
     * @param string $newDomain
     * @return bool TRUE on success, FALSE otherwise
     */
    public function changeServerName(string $domain, string $newDomain) : bool {
        $this->httpClient->queryStringBuilder()->addParameter("program", "modify-domain");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("newdomain", $newDomain);

        return $this->httpClient->sendRequest();
    }

    /**
     * Changes virtual server admin password.
     *
     * @param string $domain
     * @param string $pass
     * @return bool TRUE on success, FALSE otherwise
     */
    public function changeAdminPassword(string $domain, string $pass) : bool {
        $this->httpClient->queryStringBuilder()->addParameter("program", "modify-domain");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("pass", $pass);

        return $this->httpClient->sendRequest();
    }

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
    public function createSubServer(string $domain, string $parentDomain, string $description, array $options = []) : bool {
        $this->httpClient->queryStringBuilder()->addParameter("program", "create-domain");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("parent", $parentDomain);
        $this->httpClient->queryStringBuilder()->addParameter("dir");
        $this->httpClient->queryStringBuilder()->addParameter("web");
        foreach ($options as $key => $value) {
            if (is_numeric($key)) {
                $this->httpClient->queryStringBuilder()->addParameter($value);
            } else {
                $this->httpClient->queryStringBuilder()->addParameter($key, $value);
            }
        }
        $this->httpClient->queryStringBuilder()->addParameter("desc", $description);

        return $this->httpClient->sendRequest();
    }

    /**
     * Creates Virtual Server alias.
     *
     * @param string $domain Name of the new alias server.
     * @param string $alias Name of the target server.
     * @param string $description Sub server description.
     * @return bool TRUE on success, FALSE otherwise
     */
    public function createServerAlias(string $domain, string $alias, string $description) : bool {
        $this->httpClient->queryStringBuilder()->addParameter("program", "create-domain");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("alias", $alias);
        $this->httpClient->queryStringBuilder()->addParameter("web");
        $this->httpClient->queryStringBuilder()->addParameter("dns");
        $this->httpClient->queryStringBuilder()->addParameter("desc", $description);

        return $this->httpClient->sendRequest();
    }

    /**
     * Deletes a given sub server.
     *
     * @param $domain Domain name.
     * @return bool TRUE on success, FALSE otherwise
     */
    public function deleteServer($domain) : bool {
        $this->httpClient->queryStringBuilder()->addParameter("program", "delete-domain");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);

        return $this->httpClient->sendRequest();
    }
    
    /**
     * Creates a Virtual Server (Not an alias).
     * 
     * @param string $domain The new domain
     * @param string $password The password
     * @param string $description The description
     * @param array $options Any other needed options
     * @return bool True if success, false otherwise
     */
    public function createServer(string $domain, string $password, string $description, array $options = array()): bool {
        
        $this->httpClient->queryStringBuilder()->addParameter("program", "create-domain");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("pass", $password);
        $this->httpClient->queryStringBuilder()->addParameter("desc", $description);
        
        foreach ($options as $key => $value) {
            if (is_numeric($key)) {
                $this->httpClient->queryStringBuilder()->addParameter($value);
            } else {
                $this->httpClient->queryStringBuilder()->addParameter($key, $value);
            }
        }
        
        return $this->httpClient->sendRequest();
    }

    /**
     * Retrieving single virtual server.
     *
     * @param string $domain
     * @param string $type ServerTypes::TOP_LEVEL_SERVER, ServerTypes::SUB_SERVER
     * @return Server|SubServer
     * @throws UnknownVirtualServerTypeException
     */
    public function fetchServer(string $domain, string $type) {
        $this->httpClient->queryStringBuilder()->addParameter("program", "list-domains");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("multiline");

        $this->httpClient->sendRequest();
        $data = $this->httpClient->getResponseMessage()->data;
        $dataCount = count($data);
        if ($dataCount > 1) {
            $values = (array)$data[0]->values;
            for($i = 1; $i < $dataCount; $i++) {
                $values = array_merge($values, (array)$data[$i]->values);
            }
            $data[0]->values = (object)$values;
        }
        if ($type === ServerTypes::TOP_LEVEL_SERVER) {
            return new Server($data[0]);
        } else if ($type === ServerTypes::SUB_SERVER) {
            return new SubServer($data[0]);
        } else {
            throw new UnknownVirtualServerTypeException("Unknown server type : {$type}");
        }
    }

    /**
     * Retrieves all sub servers (addon domains) owned by a given user.
     *
     * @param string $user
     * @return array Array containing SubServer instances.
     */
    public function fetchAddonServers(string $user) : array {
        return $this->filterAddonServers($this->fetchUserSubServers($user, "subserver"));
    }

    /**
     * Retrieves all sub servers (sub domains) owned by a given user.
     * They should not be mistaken with entries created with the sub domain option in Virtualmin.
     * These are just ordinary sub servers with prefixed name
     * that is the same with the accounts primary domain name.
     *
     * @param string $user
     * @return array Array containing SubServer instances.
     */
    public function fetchSubServers(string $user) : array {
        return $this->filterSubServers($this->fetchUserSubServers($user, "subserver"));
    }

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
    public function fetchSubServersNames(string $domain, string $user) {
        $this->httpClient->queryStringBuilder()->addParameter("program", "list-domains");
        $this->httpClient->queryStringBuilder()->addParameter("user", $user);
        $this->httpClient->queryStringBuilder()->addParameter("name-only");
        $this->httpClient->queryStringBuilder()->addParameter("subserver");
        $this->httpClient->queryStringBuilder()->addParameter("no-alias");
        $this->httpClient->queryStringBuilder()->addParameter("no-reseller");

        $this->httpClient->sendRequest();
        $data = $this->httpClient->getResponseMessage()->data;
        return $this->filterSubServersNames($domain, $data);
    }

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
    public function fetchAddonServersNames(string $domain, string $user) {
        $this->httpClient->queryStringBuilder()->addParameter("program", "list-domains");
        $this->httpClient->queryStringBuilder()->addParameter("user", $user);
        $this->httpClient->queryStringBuilder()->addParameter("name-only");
        $this->httpClient->queryStringBuilder()->addParameter("subserver");
        $this->httpClient->queryStringBuilder()->addParameter("no-alias");
        $this->httpClient->queryStringBuilder()->addParameter("no-reseller");

        $this->httpClient->sendRequest();
        $data = $this->httpClient->getResponseMessage()->data;
        return $this->filterAddonServersNames($domain, $data);
    }

    /**
     * Retrieves all sub servers (alias domains) owned by a given user.
     *
     * @param string $user
     * @return array Array containing SubServer instances.
     */
    public function fetchAliasServers(string $user) : array {
        $servers = [];
        $result = $this->fetchUserSubServers($user, "alias");
        foreach ($result as $server) {
            $servers[] = new SubServer($server);
        }
        return $servers;
    }

    /**
     * Adds a Virtual server redirect records.
     *
     * @param string $domain Domain name.
     * @param string $path Domain path to redirect from.
     * @param string $destination Redirect's destination.
     * @return bool
     */
    public function addServerRedirect(string $domain, string $path, string $destination) : bool {
        $this->httpClient->queryStringBuilder()->addParameter("program", "create-redirect");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("path", $path);
        $this->httpClient->queryStringBuilder()->addParameter("redirect", $destination);

        return $this->httpClient->sendRequest();
    }

    /**
     * Deletes a Virtual server redirect record.
     *
     * @param string $domain Domain name.
     * @param $path $redirect Redirect path.
     * @return bool TRUE on success, FALSE otherwise.
     */
    public function deleteServerRedirect(string $domain, string $path) : bool {
        $this->httpClient->queryStringBuilder()->addParameter("program", "delete-redirect");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("path", $path);

        return $this->httpClient->sendRequest();
    }

    /**
     * Retrieves all redirects belonging to a given domain.
     *
     * @param string $domain Domain name.
     * @return array Array containing ServerRedirect instances.
     */
    public function fetchServerRedirects(string $domain) : array {
        $this->httpClient->queryStringBuilder()->addParameter("program", "list-redirects");
        $this->httpClient->queryStringBuilder()->addParameter("domain", $domain);
        $this->httpClient->queryStringBuilder()->addParameter("multiline");

        $this->httpClient->sendRequest();
        $redirects = [];
        $data = $this->httpClient->getResponseMessage()->data;
        foreach ($data as $redirect) {
            $redirects[] = new ServerRedirect($redirect);
        }
        return $redirects;
    }

    /**
     * Retrieves all sub servers (sub domains, addon domains or alias) owned by a given user.
     *
     * @param string $user User. The name of the user who owns the sub servers.
     * @param string $type ServerTypes::TOP_LEVEL_SERVER or ServerTypes::SUB_SERVER
     * @return array Array containing \stdClass instances.
     */
    private function fetchUserSubServers(string $user, string $type) : array {
        $this->httpClient->queryStringBuilder()->addParameter("program", "list-domains");
        $this->httpClient->queryStringBuilder()->addParameter("user", $user);
        $this->httpClient->queryStringBuilder()->addParameter($type);
        if ($type !== "alias") {
            $this->httpClient->queryStringBuilder()->addParameter("no-alias");
        }
        $this->httpClient->queryStringBuilder()->addParameter("no-reseller");
        $this->httpClient->queryStringBuilder()->addParameter("multiline");

        $this->httpClient->sendRequest();
        return $this->httpClient->getResponseMessage()->data;
    }

    /**
     * Retrieves all addon domains from a virtual servers array;
     *
     * @param array $subServers Addon domains.
     *
     * @return array Array containing SubServer instances.
     */
    private function filterAddonServers(array $subServers) : array {
        $servers = [];
        foreach ($subServers as $subServer) {
            if ($this->isSubServer($subServer) && !$this->isSubDomain($subServer)) {
                $servers[] = new SubServer($subServer);
            }
        }
        return $servers;
    }

    /**
     * Retrieves all sub domains from the virtual servers array;
     *
     * @param array $subServers Sub domains.
     *
     * @return array Array containing SubServer instances.
     */
    private function filterSubServers(array $subServers) : array {
        $servers = [];
        foreach ($subServers as $subServer) {
            if ($this->isSubServer($subServer) && $this->isSubDomain($subServer)) {
                $servers[] = new SubServer($subServer);
            }
        }
        return $servers;
    }

    /**
     * Retrieves all sub domains names.
     *
     * @param string $domain Top level server (domain) name.
     * @param array $subServers Sub domains names.
     *
     * @return array
     */
    private function filterSubServersNames(string $domain, array $subServers) : array {
        $serverNames = [];
        foreach ($subServers as $subServer) {
            if (strpos($subServer->name, $domain) !== false)
                $serverNames[] = $subServer->name;
        }
        return $serverNames;
    }

    /**
     * Retrieves all addon domains names.
     *
     * @param string $domain Top level server (domain) name.
     * @param array $subServers Sub domains names.
     *
     * @return array
     */
    private function filterAddonServersNames(string $domain, array $subServers) : array {
        $serverNames = [];
        foreach ($subServers as $subServer) {
            if (strpos($subServer->name, $domain) === false)
                $serverNames[] = $subServer->name;
        }
        return $serverNames;
    }

    /**
     * Determines if a given sub server is "sub domain".
     *
     * @param \stdClass $subServer
     *
     * @return bool TRUE if the sub server is "sub domain".
     */
    private function isSubDomain(\stdClass $subServer) : bool {
        return strpos($subServer->name, $subServer->values->parent_domain[0]) !== false;
    }

    /**
     * Determines if a given server entry is a sub server.
     * Virtualmi's list-domains command used with subserver parameter returns an associative array containing all sub servers and their ssl information
     * as a sub server \stdClass entry. We need to filter these out by "type" checking each entry.
     *
     * @param \stdClass $subServer
     * @return bool
     */
    private function isSubServer(\stdClass $subServer) : bool {
        return ($subServer->values->type[0] === "Sub-server");
    }
}
