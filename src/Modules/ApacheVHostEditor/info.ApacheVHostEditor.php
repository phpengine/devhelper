<?php

Namespace Info;

class ApacheVHostEditorInfo extends Base {

    public $hidden = false;

    public $name = "Apache Virtual Host Functions";

    public function _construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "ApacheVHostEditor" => array_merge(parent::routesAvailable(), array("add", "rm", "remove", "list",
          "enable", "en", "disable", "dis") ) );
    }

    public function routeAliases() {
      return array("vhc"=>"ApacheVHostEditor", "vhosted"=>"ApacheVHostEditor", "vhed"=>"ApacheVHostEditor",
          "vhosteditor"=>"ApacheVHostEditor", "VHostEditor"=>"ApacheVHostEditor",  "apachevhosteditor"=>"ApacheVHostEditor");
    }

    public function autoPilotVariables() {
      return array(
        "ApacheVHostEditor" => array(
          "virtualHostEditorAdditionExecute" => array(
            "virtualHostEditorAdditionExecute" => "boolean",
            "virtualHostEditorAdditionDocRoot" => "string",
            "virtualHostEditorAdditionURL" => "string",
            "virtualHostEditorAdditionIp" => "string",
            "virtualHostEditorAdditionTemplateData" => "string",
            "virtualHostEditorAdditionDirectory" => "string",
            "virtualHostEditorAdditionFileSuffix" => "string",
            "virtualHostEditorAdditionVHostEnable" => "boolean",
            "virtualHostEditorAdditionSymLinkDirectory" => "string",
            "virtualHostEditorAdditionApacheCommand" => "string", ) ,
          "virtualHostEditorDeletionExecute" => array(
            "virtualHostEditorDeletionExecute" => "boolean",
            "virtualHostEditorDeletionDirectory" => "string",
            "virtualHostEditorDeletionTarget" => "string",
            "virtualHostEditorDeletionVHostDisable" => "boolean",
            "virtualHostEditorDeletionSymLinkDirectory" => "string",
            "virtualHostEditorDeletionApacheCommand" => "string", ) ,
        ) ,
      );
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This command is part of Default Modules and handles Apache VHosts Functions.

  ApacheVHostEditor, apachevhosteditor, vhosteditor, vhc, vhosted

          - add
          create a Virtual Host
          example: dapperstrano vhc add

          - rm
          remove a Virtual Host
          example: dapperstrano vhc rm

          - list
          List current Virtual Hosts
          example: dapperstrano vhc list

          - enable
          enable a Server Block
          example: dapperstrano vhc enable

          - disable
          disable a Server Block
          example: dapperstrano vhc disable

HELPDATA;
      return $help ;
    }


  public function generatorCodeInjection($step=null) {
    $inject = <<<'INJECT'
//
// // This function will set the vhost template for your Virtual Host
// // You need to call this from your constructor
// private function calculateVHostDocRoot() {
        $serverAlias = str_replace("www", "*", $this->virtualHostEditorAdditionURL);
INJECT;
$inject .= "\n".'//   $this->steps['.$step.']["ApacheVHostEditor"]["virtualHostEditorAdditionDocRoot"] = getcwd();'."\n";
$inject .= <<<'INJECT'
// }
//
// // This function will set the vhost template for your Virtual Host
// // You need to call this from your constructor
// private function setVHostTemplate() {
INJECT;
    $inject .= "\n".'//   $this->steps['.$step.']["ApacheVHostEditor"]["virtualHostEditorAdditionTemplateData"] = '."\n";
    $inject .= <<<'INJECT'
//  <<<'TEMPLATE'
//  NameVirtualHost ****IP ADDRESS****:80
//  <VirtualHost ****IP ADDRESS****:80>
//    ServerAdmin webmaster@localhost
// 	  ServerName ****SERVER NAME****
// 	  DocumentRoot ****WEB ROOT****/src
// 	  <Directory ****WEB ROOT****/src>
// 		  Options Indexes FollowSymLinks MultiViews
// 		  AllowOverride All
// 		  Order allow,deny
// 		  allow from all
// 	  </Directory>
//    ErrorLog /var/log/apache2/error.log
//    CustomLog /var/log/apache2/access.log combined
//  </VirtualHost>
//
//  NameVirtualHost ****IP ADDRESS****:443
//  <VirtualHost ****IP ADDRESS****:443>
// 	  ServerAdmin webmaster@localhost
// 	  ServerName ****SERVER NAME****
// 	  DocumentRoot ****WEB ROOT****/src
//    # SSLEngine on
// 	  # SSLCertificateFile /etc/apache2/ssl/ssl.crt
//    # SSLCertificateKeyFile /etc/apache2/ssl/ssl.key
//    # SSLCertificateChainFile /etc/apache2/ssl/bundle.crt
// 	  <Directory ****WEB ROOT****/src>
// 		  Options Indexes FollowSymLinks MultiViews
//  		AllowOverride All
//		  Order allow,deny
//	  	allow from all
//  	</Directory>
//    ErrorLog /var/log/apache2/error.log
//    CustomLog /var/log/apache2/access.log combined
//  </VirtualHost>
//TEMPLATE;
//}
//
INJECT;
    return $inject ;

    }

}