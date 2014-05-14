<?php

/*************************************
 *      Generated Autopilot file      *
 *     ---------------------------    *
 *Autopilot Generated By Dapperstrano *
 *     ---------------------------    *
 *************************************/

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;

    private $time ;

    public function __construct() {
        $this->setTime() ;
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin uninstalling our application code"  ), ) ),

                array ( "Logging" => array( "log" => array( "log-message" => "First remove the directory" ), ) ),
                array ( "GitClone" => array( "rm" => array (
                    "guess" => true ,
                    "repository-url" => "<%tpl.php%>dap_git_repo_url</%tpl.php%>",
                    "custom-clone-dir" => $this->getTime() ,
                    "custom-branch" => "<%tpl.php%>dap_git_custom_branch</%tpl.php%>"
                ), ), ),

                array ( "Logging" => array( "log" =>
                    array( "log-message" => "Next remove our virtual host"),
                ) ),
                array ( "VHostEditor" => array(
                    "virtualHostEditorDeletionExecute" => "boolean",
                    "virtualHostEditorDeletionDirectory" => "/etc/apache2/sites-available",
                    "virtualHostEditorDeletionTarget" => "<%tpl.php%>dap_apache_vhost_url</%tpl.php%>",
                    "virtualHostEditorDeletionVHostDisable" => false,
                    "virtualHostEditorDeletionSymLinkDirectory" => "/etc/apache2/sites-enabled",
                    "virtualHostEditorDeletionApacheCommand" => "apache2",
                ) , ) ,
                array ( "VHostEditor" => array( "rm" => array (
                    "guess" => true ,
                    "vhe-docroot" => "<%tpl.php%>dap_proj_cont_dir</%tpl.php%>{$this->getTime()}",
                    "vhe-url" => "<%tpl.php%>dap_apache_vhost_url</%tpl.php%>",
                    "vhe-ip-port" => "<%tpl.php%>dap_apache_vhost_ip</%tpl.php%>",
                    "vhe-vhost-dir" => "/etc/apache2/sites-available" ,
                    "vhe-template" => $this->getTemplate() ,
                    "vhe-file-ext" => ""
                ), ), ),
                array ( "Logging" => array( "log" => array(
                    "log-message" => "The application is installed now so lets do our versioning"
                ), ), ),
                array ( "Version" => array( "latest" => array(
                    "container" => "<%tpl.php%>dap_proj_cont_dir</%tpl.php%>",
                    "limit" => "<%tpl.php%>dap_version_num_revisions</%tpl.php%>"
                ), ), ),

                array ( "Logging" => array( "log" => array (
                    "log-message" => "Now lets restart Apache to stop serving our application"
                ), ), ),
                array ( "ApacheControl" => array( "restart" => array() , ) , ) ,

                array ( "Logging" => array( "log" => array( "log-message" => "Our deployment is done"),) ),
            );


    }


    private function setTime() {
        $this->time = time() ;
    }

    private function getTime() {
        return $this->time ;
    }

    private function getTemplate() {
        $template =
            <<<'TEMPLATE'
           NameVirtualHost ****IP ADDRESS****:80
 <VirtualHost ****IP ADDRESS****:80>
   ServerAdmin webmaster@localhost
 	ServerName ****SERVER NAME****
 	DocumentRoot ****WEB ROOT****/src
 	<Directory ****WEB ROOT****/src>
 		Options Indexes FollowSymLinks MultiViews
 		AllowOverride All
 		Order allow,deny
 		allow from all
 	</Directory>
   ErrorLog /var/log/apache2/error.log
   CustomLog /var/log/apache2/access.log combined
 </VirtualHost>

 NameVirtualHost ****IP ADDRESS****:443
 <VirtualHost ****IP ADDRESS****:443>
 	 ServerAdmin webmaster@localhost
 	 ServerName ****SERVER NAME****
 	 DocumentRoot ****WEB ROOT****/src
   # SSLEngine on
 	 # SSLCertificateFile /etc/apache2/ssl/ssl.crt
   # SSLCertificateKeyFile /etc/apache2/ssl/ssl.key
   # SSLCertificateChainFile /etc/apache2/ssl/bundle.crt
 	 <Directory ****WEB ROOT****/src>
 		 Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</Directory>
  ErrorLog /var/log/apache2/error.log
  CustomLog /var/log/apache2/access.log combined
  </VirtualHost>
TEMPLATE;

        return $template ;
    }

}
