<?php

Namespace Model;

class Dapperfy extends Base {

    private $environments ;
    private $replacements ;
    private $environmentSpecificReplacements ;
    private $crossEnvironmentReplacements ;

    public function askWhetherToDapperfy() {
        if ($this->askToScreenWhetherToDapperfy() != true) { return false; }
        $this->setCrossEnvironmentReplacements() ;
        $this->setEnvironmentSpecificReplacements() ;
        $this->getEnvironments() ;
        $this->doDapperfy() ;
        return true;
    }

    public function askToScreenWhetherToDapperfy() {
      $question = 'Dapperfy This?';
      return self::askYesOrNo($question, true);
    }

    public function setEnvironmentSpecificReplacements() {
      $this->environmentSpecificReplacements = array(
        "****projectContainerDirectory****",
        "****gitCheckoutProjectOriginRepo****",
        "****gitCheckoutProjectCustomBranch****",
        "****virtualHostEditorAdditionURL****",
        "****virtualHostEditorAdditionIp****",
        "****dbIp****",
        "****dbAppUserName****",
        "****dbAppUserPass****",
        "****dbName****",
        "****dbRootUserName****",
        "****dbRootUserPass****",
      );
    }

    public function setCrossEnvironmentReplacements() {
      $this->crossEnvironmentReplacements = array(
        "****dbPlatform****",
      );
    }

    public function getEnvironments() {
      $i = 0 ;
      $crossEnvironmentValues = array();
      echo "These questions apply to all environments: \n";
      foreach ($this->crossEnvironmentReplacements as $replacementQuestion) {
        $crossEnvironmentValues[$replacementQuestion] = self::askForInput("Value for: ".$replacementQuestion); }

      $allProjectEnvs = \Model\AppConfig::getProjectVariable("environments");
      if (count($allProjectEnvs) > 0) {
        $question = 'Dapperfy This?';
        $useProjEnvs = self::askYesOrNo($question, true);
        if ($useProjEnvs == true ) {
          $this->environments = $allProjectEnvs;
          return; } }

      $more_servers = true;
      while ($more_servers == true) {
        if (count($this->environments)==0) {
          echo "Environment Number $i: \n";
          $this->environments[$i]["****CURRENT_ENVIRONMENT_NAME****"] = self::askForInput("Value for: Name", true);
          $this->environments[$i]["****CURRENT_ENVIRONMENT_TEMP_DIR****"] = self::askForInput("Value for: Temp Dir", true);
          $this->environments[$i]["****CURRENT_ENVIRONMENT_NUMBER_REVISIONS****"] = self::askForInput("Value for: Number Revisions", true);
          $this->environments[$i]["****CURRENT_ENVIRONMENT_SERVERS_ARRAY_TEXT****"] = $this->getServerArrayText($this->getServers());
          foreach ($this->environmentSpecificReplacements as $replacementQuestion) {
            $this->environments[$i][$replacementQuestion] = self::askForInput("Value for: ".$replacementQuestion); }
          foreach ($this->crossEnvironmentReplacements as $replacementQuestion) {
            $this->environments[$i][$replacementQuestion] = $crossEnvironmentValues[$replacementQuestion]; }}
        else {
          $question = 'Do you want to add another environment?';
          $add_another_server = self::askYesOrNo($question);
          if ($add_another_server == true) {
            echo "Environment Number $i: \n";
            $this->environments[$i]["****CURRENT_ENVIRONMENT_NAME****"] = self::askForInput("Value for: Name", true);
            $this->environments[$i]["****CURRENT_ENVIRONMENT_TEMP_DIR****"] = self::askForInput("Value for: Temp Dir", true);
            $this->environments[$i]["****CURRENT_ENVIRONMENT_NUMBER_REVISIONS****"] = self::askForInput("Value for: Number Revisions", true);
            $this->environments[$i]["****CURRENT_ENVIRONMENT_SERVERS_ARRAY_TEXT****"] = $this->getServerArrayText($this->getServers());
            foreach ($this->environmentSpecificReplacements as $replacementQuestion) {
              $this->environments[$i][$replacementQuestion] = self::askForInput("Value for: ".$replacementQuestion); }
            foreach ($this->crossEnvironmentReplacements as $replacementQuestion) {
              $this->environments[$i][$replacementQuestion] = $crossEnvironmentValues[$replacementQuestion]; }}
          else {
            $more_servers = false; } }
        $i++; }

    }

    public function getServers() {
      $servers = array();
      $serverAttributes = array("target", "user", "password");
      $keepGoing = true ;
      $question = 'Enter Servers - this is an array of entries';
      while ($keepGoing == true) {
        $tinierArray = array();
        echo $question."\n";
        foreach ($serverAttributes as $questionTarget) {
          $miniQuestion = 'Enter '.$questionTarget.' ?';
          $tinierArray[$questionTarget] = self::askForInput($miniQuestion, true); }
        $servers[] = $tinierArray;
        $keepGoingQuestion = 'Add Another Server? (Y/N)';
        $keepGoingResult = self::askForInput($keepGoingQuestion, true);
        $keepGoing = ($keepGoingResult == "Y" || $keepGoingResult == "y") ? true : false ; }
      return $servers;
    }

    public function getServerArrayText($serversArray) {
      $serversText = "";
      foreach($serversArray as $serverArray) {
        $serversText .= 'array(';
        $serversText .= '"target" => "'.$serverArray["target"].'", ';
        $serversText .= '"user" => "'.$serverArray["user"].'", ';
        $serversText .= '"pword" => "'.$serverArray["password"].'", ';
        $serversText .= '),'."\n"; }
      return $serversText;
    }

    private function doDapperfy() {
      $templatesDir = str_replace("Model", "Templates", dirname(__FILE__) ) ;
      $templates = scandir($templatesDir);
      $this->writeEnvsToProjectFile();
      foreach ($this->environments as $environment) {
        foreach ($templates as $template) {
          if (!in_array($template, array(".", ".."))) {
            $fileData = $this->loadFile($templatesDir.DIRECTORY_SEPARATOR.$template);
            $fileData = $this->dataChange($fileData, $environment);
            $this->saveFile($template, $environment["****CURRENT_ENVIRONMENT_NAME****"], $fileData); } } }
    }

    private function checkIsDHProject() {
      return file_exists('dhproj');
    }

    private function writeEnvsToProjectFile(){
      if ($this->checkIsDHProject()){
        \Model\AppConfig::setProjectVariable("environments", $this->environments); }
    }

    private function deleteEnvsFromProjectFile(){
      if ($this->checkIsDHProject()){
        $allProjectVHosts = \Model\AppConfig::getProjectVariable("environments");
        for ($i = 0; $i<=count($allProjectVHosts) ; $i++ ) {
          if (isset($allProjectVHosts[$i]) && in_array($allProjectVHosts[$i], $this->vHostForDeletion)) {
            unset($allProjectVHosts[$i]); } }
        \Model\AppConfig::setProjectVariable("environments", $allProjectVHosts); }
    }

    private function loadFile($fileToLoad) {
      $command = 'cat '.$fileToLoad;
      $fileData = self::executeAndLoad($command);
      return $fileData ;
    }

    private function saveFile($fileName, $environmentName, $fileData) {
      $newFileName = str_replace("environment", $environmentName, $fileName ) ;
      $autosDir = getcwd().DIRECTORY_SEPARATOR.'build'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'dapperstrano'.DIRECTORY_SEPARATOR.'autopilots';
      if (!file_exists($autosDir)) { mkdir ($autosDir, 0777, true); }
      if (!file_exists(getcwd().DIRECTORY_SEPARATOR.'src')) { mkdir (getcwd().DIRECTORY_SEPARATOR.'src', 0777, true); }
      return file_put_contents($autosDir.DIRECTORY_SEPARATOR.$newFileName, $fileData);
    }

    private function dataChange($fileData, $environment){
      $newFileData = strtr($fileData, $environment);
      return $newFileData ;
    }

}