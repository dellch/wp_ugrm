<?php

$composer_config_path = APP_ROOT . '/config/ugrm-ldap/ldap-config.php';
$local_config_path = plugin_dir_path( __FILE__ ) . 'ldap-config.php';
file_exists($composer_config_path) ? include_once $composer_config_path : include_once $local_config_path;

class UgrmLdap {
    private $link;
    private $binddn;
    private $pw;
    private $ldap_uri;
    private $basedn;
    private $log_path;

    function __construct() {
        $config = UgrmLdapConfig::$configuration;
        $this->binddn = $config['binddn'];
        $this->pw = $config['pw'];
        $this->basedn = $config['basedn'];
        $this->ldap_uri = $config['ldapUri'];
        $this->log_path = $config['queryLogPath'];
        $this->setLink();
    }

    function __destruct() {
        ldap_unbind($this->link);
    }

    private function setLink() {
        $ldap_resource = ldap_connect($this->ldap_uri);
        ldap_set_option($ldap_resource, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap_resource, LDAP_OPT_NETWORK_TIMEOUT, 2);
        ldap_set_option($ldap_resource, LDAP_OPT_REFERRALS, 0);
        $is_bound = ldap_bind($ldap_resource, $this->binddn, $this->pw);
        if($is_bound) {
            $this->link = $ldap_resource;
        } else {
            $error = ldap_error($ldap_resource);
            wp_die('UGRM LDAP link failed to connect. '.$error, 'UGRM LDAP connection failure');
        }
    }

    private function logQuery($query_time) {
        date_default_timezone_set('America/New_York');
        $current_time = date(DATE_RFC2822);
        $entry = $current_time." - Query took ".$query_time." seconds to return.\n";
        file_put_contents($this->log_path, $entry, FILE_APPEND);
    }

    private function getResult($search_filter, $search_attribute) {
        $start_time = microtime(true);
        $result = ldap_search($this->link, $this->basedn, $search_filter, $search_attribute);
        $end_time = microtime(true);
        if(!$result) {
            $error = ldap_error($this->link);
            wp_die('UGRM LDAP query returned error. '.$error, 'UGRM LDAP query failure');
        }
        $query_time = $end_time - $start_time;
        return $result;
    }

    public function glidHasGroup($glid, $group_dn) {
        $search_filter = "(&(samaccountname={$glid})(memberOf={$group_dn}))";
        $search_attribute = array('samaccountname');
        $result = $this->getResult($search_filter, $search_attribute);
        $count = ldap_count_entries($this->link, $result);
        return ($count !== 0);
    }

    public function getUFADGroupsDN($glid) {
        $search_filter = "(samaccountname={$glid})";
        $search_attribute = array('memberOf');
        $result = $this->getResult($search_filter, $search_attribute);
        $entries = ldap_get_entries($this->link, $result);
        return $entries[0]['memberof'];
    }

}