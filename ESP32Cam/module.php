<?
    // Klassendefinition
    class ESP32Cam extends IPSModule 
    { 
	// Überschreibt die interne IPS_Create($id) Funktion
        public function Create() 
        {
            	// Diese Zeile nicht löschen.
            	parent::Create();
 	    	$this->RegisterPropertyBoolean("Open", false);
		$this->RegisterPropertyString("IPAddress", "127.0.0.1");
        }
 	
	public function GetConfigurationForm() 
	{ 
		$arrayStatus = array(); 
		$arrayStatus[] = array("code" => 101, "icon" => "inactive", "caption" => "Instanz wird erstellt"); 
		$arrayStatus[] = array("code" => 102, "icon" => "active", "caption" => "Instanz ist aktiv");
		$arrayStatus[] = array("code" => 104, "icon" => "inactive", "caption" => "Instanz ist inaktiv");
				
		$arrayElements = array(); 
		$arrayElements[] = array("name" => "Open", "type" => "CheckBox",  "caption" => "Aktiv"); 
		$arrayElements[] = array("type" => "ValidationTextBox", "name" => "IPAddress", "caption" => "IP");
 		
		$arrayActions = array();
		$arrayActions[] = array("type" => "Label", "label" => "Test Center"); 
		$arrayActions[] = array("type" => "TestCenter", "name" => "TestCenter");
		
 		return JSON_encode(array("status" => $arrayStatus, "elements" => $arrayElements, "actions" => $arrayActions)); 		 
 	}       
	   
        // Überschreibt die intere IPS_ApplyChanges($id) Funktion
        public function ApplyChanges() 
        {
            	// Diese Zeile nicht löschen
            	parent::ApplyChanges();
		
		// Profil anlegen
		
	
		// Statusvariablen
		$this->RegisterVariableInteger("xclk", "XCLK MHz", "", 10);
		$this->EnableAction("xclk");
		
		// Resulution
		
		$this->RegisterVariableInteger("quality", "Quality", "", 30);
		$this->EnableAction("quality");

		$this->RegisterVariableInteger("brightness", "Brightness", "", 40);
		$this->EnableAction("brightness");

		$this->RegisterVariableInteger("contrast", "Contrast", "", 50);
		$this->EnableAction("contrast");

		$this->RegisterVariableInteger("saturation", "Saturation", "", 60);
		$this->EnableAction("saturation");

		$this->RegisterVariableInteger("special_effect", "Special Effect", "", 70);
		$this->EnableAction("special_effect");

		$this->RegisterVariableBoolean("awb", "AWB", "", 80);
		$this->EnableAction("awb");
    
    		$this->RegisterVariableBoolean("awb_gain", "AWB Gain", "", 90);
		$this->EnableAction("awb_gain");

		$this->RegisterVariableInteger("wb_mode", "WB Mode", "", 100);
		$this->EnableAction("wb_mode");

		$this->RegisterVariableBoolean("aec", "AEC Sensor", "", 110);
		$this->EnableAction("aec");

		$this->RegisterVariableBoolean("aec2", "AEC DSP", "", 120);
		$this->EnableAction("aec2");

		$this->RegisterVariableInteger("ae_level", "ae_level", "", 130);
		$this->EnableAction("ae_level");

		$this->RegisterVariableBoolean("agc", "AGC", "", 140);
		$this->EnableAction("agc");



		
		$this->RegisterVariableInteger("0xd3", "Register 0xd3", "", 10);
		$this->EnableAction("0xd3");

		$this->RegisterVariableInteger("0x111", "Register 0x111", "", 20);
		$this->EnableAction("0x111");

		$this->RegisterVariableInteger("0x132", "Register 0x132", "", 30);
		$this->EnableAction("0x132");
		 
    		$this->RegisterVariableInteger("pixformat", "Pixformat", "", 50);
		$this->EnableAction("pixformat");

		$this->RegisterVariableInteger("framesize", "Framesize", "", 60);
		$this->EnableAction("framesize");
    
		$this->RegisterVariableInteger("sharpness", "Sharpness", "", 110);
		$this->EnableAction("sharpness");

    		$this->RegisterVariableInteger("aec_value", "aec_value", "", 110);
		$this->EnableAction("aec_value");

    		$this->RegisterVariableInteger("agc_gain", "AGC Gain", "", 110);
		$this->EnableAction("agc_gain");
    
   		$this->RegisterVariableInteger("gainceiling", "gainceiling", "", 110);
		$this->EnableAction("gainceiling");
		
    		$this->RegisterVariableInteger("bpc", "BPC", "", 110);
		$this->EnableAction("bpc");
		
    		$this->RegisterVariableInteger("wpc", "WPC", "", 110);
		$this->EnableAction("wpc");

		$this->RegisterVariableInteger("raw_gma", "Raw GMA", "", 110);
		$this->EnableAction("raw_gma");
   
    		$this->RegisterVariableInteger("lenc", "Lens Correction", "", 110);
		$this->EnableAction("lenc");

		$this->RegisterVariableInteger("hmirror", "H-Mirror", "", 110);
		$this->EnableAction("hmirror");

		$this->RegisterVariableInteger("dcw", "dcw", "", 110);
		$this->EnableAction("dcw");
    
    		$this->RegisterVariableInteger("colorbar", "Color Bar", "", 110);
		$this->EnableAction("colorbar");

		$this->RegisterVariableInteger("led_intensity", "LED Intensity", "", 110);
		$this->EnableAction("led_intensity");
    
    
		
		
		If ($this->HasActiveParent() == true) {	
			If ($this->ReadPropertyBoolean("Open") == true) {
				If ($this->GetStatus() <> 102) {
					$this->SetStatus(102);
				}
			}
			else {
				If ($this->GetStatus() <> 104) {
					$this->SetStatus(104);
				}
			}
		}
	}
	
	public function RequestAction($Ident, $Value) 
	{
		switch($Ident) {
		case "ManuellSwitch":
			$this->Switch($Value);
			
			break;
		
	
		default:
		    throw new Exception("Invalid Ident");
		}
	}
	    
	// Beginn der Funktionen
	public function GetState
	{
		If ($this->ReadPropertyBoolean("Open") == true) {
				
		}
	}
	
	
	

}
?>
