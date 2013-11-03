<?php

Namespace Controller ;

class NginxSBEditor extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) { return $isHelp; }
        $action = $pageVars["route"]["action"];

        $NginxSBEditorModel = new \Model\NginxSBEditor($pageVars["route"]["extraParams"]);

        if ($action=="list") {
            $this->content["NginxSBEditorResult"] = $NginxSBEditorModel->askWhetherToListServerBlock();
            return array ("type"=>"view", "view"=>"NginxSBEditor", "pageVars"=>$this->content); }

        else if ($action=="add") {
            $this->content["NginxSBEditorResult"] = $NginxSBEditorModel->askWhetherToCreateServerBlock();
            return array ("type"=>"view", "view"=>"NginxSBEditor", "pageVars"=>$this->content); }

        else if ($action=="remove" || $action=="rm") {
            $this->content["NginxSBEditorResult"] = $NginxSBEditorModel->askWhetherToDeleteServerBlock();
            return array ("type"=>"view", "view"=>"NginxSBEditor", "pageVars"=>$this->content); }

        else if ($action=="enable" || $action=="en") {
            $this->content["NginxSBEditorResult"] = $NginxSBEditorModel->askWhetherToEnableServerBlock();
            return array ("type"=>"view", "view"=>"NginxSBEditor", "pageVars"=>$this->content); }

        else if ($action=="disable" || $action=="dis") {
            $this->content["NginxSBEditorResult"] = $NginxSBEditorModel->askWhetherToDisableServerBlock();
            return array ("type"=>"view", "view"=>"NginxSBEditor", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid ServerBlock Creator Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}