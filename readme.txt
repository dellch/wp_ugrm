=== Plugin Name ===
Contributors: warren.brown,taylorwilson
Donate link: http://www.flmnh.ufl.edu/
Tags: UF, UFAD authentication, login, SAML, shibboleth
Requires at least: 3.2.1
Tested up to: 4.9.4
Stable tag: 2.0

This plugin extends the Shibboleth plugin to work with UFAD & Shibboleth at the University of Florida. Developed at the Florida Museum of Natural History.

== Description ==

Since this plugin extends the Shibboleth plugin, you must first have the Shibboleth plugin, available from http://wordpress.org/extend/plugins/shibboleth/
installed and activated. Otherwise, the plugin will fail to activate as the shibboleth_user_role filter hook will not be registered.

To use this plugin, you must already have the following setup on your server:
1. The above Shibbleth plugin.
2. A UFAD group created for each of the Wordpress roles (administrator, editor, author, contributor, and subscriber).

== Installation ==

1. Install, activate, configure and test the Shibbloeth plugin. When it is working, procede.
2. Create a UGRM directory in `/wp-content/plugins/` directory
3. Extract the contents of the UGRM.tar.gz plugin archive to the `/wp-content/plugins/UGRM` directory
4. Populate UgrmLdapConfig::$configuration attribute located at `/wp-content/plugins/UGRM/ldap-config.php`.  Options are:
    a. binddn - The Distinguished Name (DN) of the user or service account that will query LDAP server for group membership.
    b. pw - The password for the user or service account connecting to ldap. (binddn user)
    c. basedn - The base DN for the LDAP directory.
    d. ldapUri - The URI of the ldap server.
5. Activate the plugin through the 'Plugins' menu in WordPress
6. Populate the 'UFAD Groups to Roles' options page under the 'Settings' menu in Wordpress.

== Frequently Asked Questions ==

= It's not working. What should I check? =

Check for typos on the options page and ensure you've spelled your UFAD groups correctly.

`If $_SERVER['glid']` for Apache or `$_SERVER['HTTP_glid']` for IIS is not present, then complete
the correct application to have glid included in UF Shibboleth URN.

Verify that you can make a connection to the ldap server specified in ldapUri set in the options file.  You may do this by launching ldp.ext 
in Windows and inputting the binddn and password from the options file.  You could also use any of the various ldap modules for any 
programming language to test.

= What if I've done all that and it still doesn't work? =

Contact the plugin author(s), who will respond in a vague and unspecified amount of time.

== Screenshots ==

1.  Plugin Screenshot
2.  Plugin Config Options

== Changelog ==
= 2.0 =
* Updates UGRM.php to use UgrmLdap class from ldap.php to query UFAD LDAP server to get group membership by shibboleth provided `glid` apache server variable.
* Adds ldap-config.php containing LDAP connection parameters.
* Corrects bug in options.php where $_SERVER superglobal array keys were not quoted, emitting an error for undeclared constant.

= 1.7.1 =
* Corrected typo in code. Minor fix, but very large impact.

= 1.7 =
* Discovered that with multisite enabled, the server variables will sometimes present as prepended with REDIRCT_ when in a subsite. E.G. UFADGroupsDN will sometimes appear
REDIRECT_UFADGroupsDN. The code has been extended to accomdate this.
*As a side note, the Shibboleth plugin UGRM extends appears to have been abandoned. As we've already made code changes to enable the Shibboleth plugin to work
with the new Wordpress enabled for multisite, and we had to graft on further changes for the REDIRECT_ behavior, we plan to release a fork of the Shibboleth plugin.

= 1.6 =
 * Fixed a glaring bug in when "Force Shibboleth return target to HTTPS" was checked and return target was already https the target would be munged to httpss.
 * Discovered Shibboleth on IIS prepends all Shibboleth server variables with a HTTP_ prefix because the variables are populated via CGI as IIS does not support
 environment variables (for details, check out: https://wiki.shibboleth.net/confluence/display/SHIB2/NativeSPAttributeAccess). Plugin now inspects SERVER_SOFTWARE
 variable and adjusts accordingly.

= 1.5 =
 * Fixed header in UGRM.php to resolve current version display on Wordpress site.
 
= 1.4 =
 * Attempting to correct Wordpress SVN tagging for current
 
= 1.3 =
 * Still working on SVN versioning

= 1.2 =
 * New version number to resolve wonkyness with Wordpress SVN.

= 1.1 =
 * Added a configuration option for requiring HTTPS on the return target. This hooks into the Shibboleth provided shibboleth_seesion_initiator_url filter and ensures
the return target uses HTTPS. This allows you seemless provide a Shibboleth integrated Wordpress site where the content side is delivered via HTTP and the admin
side is delivered VIA HTTPS.  The default  Shibboleth plugin behavior is to construct the return target using the current protocol, e.g. if you click the login link from
HTTP, your return target would be for HTTP.  UGRM now allows you to overide this behavior and alwasy use a HTTPS return target.

= 1.0 =
* Initial Release

== Upgrade Notice ==
= 1.6 =
Important upgrade. Bug fix for "Force return target to HTTPS" feature and adds IIS support.

= 1.5 =
Fixed header in UGRM.php to resolve current version display on Wordpress site.

= 1.4 =
Attemtpin to correct the Wordpress SVN current version labeling

= 1.3 =
Still working on SVN versioning

= 1.2 =
New version number to resolved wonkyness with Wordpress SVN.

= 1.1 =
Added functionality to allow UGRM to override return login target to always be HTTPS.

= 1.0 =
Initial Release. 
