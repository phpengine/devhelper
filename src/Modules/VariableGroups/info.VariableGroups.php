<?php

Namespace Info;

class VariableGroupsInfo extends PTConfigureBase {

    public $hidden = false;

    public $name = "Install files with placeholders or lines replaced at runtime";

    public function __construct() {
      parent::__construct();
    }

    public function routesAvailable() {
      return array( "VariableGroups" =>  array_merge(parent::routesAvailable(), array("install") ) );
    }

    public function routeAliases() {
      return array("VariableGroups"=>"VariableGroups", "template"=>"VariableGroups");
    }

    public function helpDefinition() {
      $help = <<<"HELPDATA"
  This module allows you to install a templated file with new values.

  VariableGroups, VariableGroups, template

        - install
        Installs a template
        example: ptconfigure template install
        example: ptconfigure template install -yg
        example: ptconfigure template install -yg --source="" --target=""

HELPDATA;
      return $help ;
    }

}