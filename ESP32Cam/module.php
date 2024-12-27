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
		$this->RegisterProfileInteger("ESP32Cam.Framesize", "Image", "", "", 0, 12, 0);
		IPS_SetVariableProfileAssociation("ESP32Cam.Framesize", 0, "THUMB (96x96)", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.Framesize", 1, "QQVGA (160x120)", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.Framesize", 3, "HQVGA (240x176)", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.Framesize", 5, "QVGA (320x240)", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.Framesize", 6, "CIF (400x296)", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.Framesize", 7, "HVGA (480x320)", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.Framesize", 8, "VGA (640x480)", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.Framesize", 9, "SVGA (800x600)", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.Framesize", 10, "XGA (1024x768)", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.Framesize", 11, "HD (1280x720)", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.Framesize", 12, "SXGA (1280x1024)", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.Framesize", 13, "UXGA (1600x1200)", "Image", -1);

		$this->RegisterProfileInteger("ESP32Cam.SpecialEffect", "Image", "", "", 0, 7, 0);
		IPS_SetVariableProfileAssociation("ESP32Cam.SpecialEffect", 0, "No Effect", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.SpecialEffect", 1, "Negative", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.SpecialEffect", 2, "Grayscale", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.SpecialEffect", 3, "Red Tint", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.SpecialEffect", 4, "Green Tint", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.SpecialEffect", 5, "Blue Tint", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.SpecialEffect", 6, "Sepia", "Image", -1);

		$this->RegisterProfileInteger("ESP32Cam.WBMode", "Image", "", "", 0, 5, 0);
		IPS_SetVariableProfileAssociation("ESP32Cam.WBMode", 0, "Auto", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.WBMode", 1, "Sunny", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.WBMode", 2, "Cloudy", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.WBMode", 3, "Office", "Image", -1);
		IPS_SetVariableProfileAssociation("ESP32Cam.WBMode", 4, "Home", "Image", -1);
		
		// Statusvariablen
		$this->RegisterVariableInteger("xclk", "XCLK MHz", "", 10);
		$this->EnableAction("xclk");
		
		$this->RegisterVariableInteger("framesize", "Framesize", "ESP32Cam.Framesize", 20);
		$this->EnableAction("framesize");
		
		$this->RegisterVariableInteger("quality", "Quality", "", 30);
		$this->EnableAction("quality");

		$this->RegisterVariableInteger("brightness", "Brightness", "", 40);
		$this->EnableAction("brightness");

		$this->RegisterVariableInteger("contrast", "Contrast", "", 50);
		$this->EnableAction("contrast");

		$this->RegisterVariableInteger("saturation", "Saturation", "", 60);
		$this->EnableAction("saturation");

		$this->RegisterVariableInteger("special_effect", "Special Effect", "ESP32Cam.SpecialEffect", 70);
		$this->EnableAction("special_effect");

		$this->RegisterVariableBoolean("awb", "AWB", "", 80);
		$this->EnableAction("awb");
    
    		$this->RegisterVariableBoolean("awb_gain", "AWB Gain", "", 90);
		$this->EnableAction("awb_gain");

		$this->RegisterVariableInteger("wb_mode", "WB Mode", "ESP32Cam.WBMode", 100);
		$this->EnableAction("wb_mode");

		$this->RegisterVariableBoolean("aec", "AEC Sensor", "", 110);
		$this->EnableAction("aec");

		$this->RegisterVariableBoolean("aec2", "AEC DSP", "", 120);
		$this->EnableAction("aec2");

		$this->RegisterVariableInteger("ae_level", "ae_level", "", 130);
		$this->EnableAction("ae_level");

		$this->RegisterVariableBoolean("agc", "AGC", "", 140);
		$this->EnableAction("agc");

   		$this->RegisterVariableInteger("gainceiling", "Gain Ceiling", "", 150);
		$this->EnableAction("gainceiling");

		$this->RegisterVariableBoolean("bpc", "BPC", "", 160);
		$this->EnableAction("bpc");
		
    		$this->RegisterVariableBoolean("wpc", "WPC", "", 170);
		$this->EnableAction("wpc");
		
		$this->RegisterVariableBoolean("raw_gma", "Raw GMA", "", 180);
		$this->EnableAction("raw_gma");

		$this->RegisterVariableBoolean("lenc", "Lens Correction", "", 190);
		$this->EnableAction("lenc");

		$this->RegisterVariableBoolean("hmirror", "H-Mirror", "", 200);
		$this->EnableAction("hmirror");

		// V-Flip
		
		$this->RegisterVariableBoolean("dcw", "DCW (Downsize EN)", "", 220);
		$this->EnableAction("dcw");
		
		$this->RegisterVariableBoolean("colorbar", "Color Bar", "", 230);
		$this->EnableAction("colorbar");

		$this->RegisterVariableInteger("led_intensity", "LED Intensity", "~Intensity.255", 240);
		$this->EnableAction("led_intensity");
    

		/*
		$this->RegisterVariableInteger("0xd3", "Register 0xd3", "", 10);
		$this->EnableAction("0xd3");

		$this->RegisterVariableInteger("0x111", "Register 0x111", "", 20);
		$this->EnableAction("0x111");

		$this->RegisterVariableInteger("0x132", "Register 0x132", "", 30);
		$this->EnableAction("0x132");
		 
    		$this->RegisterVariableInteger("pixformat", "Pixformat", "", 50);
		$this->EnableAction("pixformat");

		$this->RegisterVariableInteger("sharpness", "Sharpness", "", 110);
		$this->EnableAction("sharpness");

    		$this->RegisterVariableInteger("aec_value", "aec_value", "", 110);
		$this->EnableAction("aec_value");

    		$this->RegisterVariableInteger("agc_gain", "AGC Gain", "", 110);
		$this->EnableAction("agc_gain");
    		*/
		
		$this->RegisterMediaObject("Capture", "Capture_".$this->InstanceID, 1, $this->InstanceID, 300, true, "Capture.jpg");
    		
		$this->RegisterVariableString("Stream", "Stream", "~HTMLBox", 310);
		
    
		
		
		If ($this->HasActiveParent() == true) {	
			If ($this->ReadPropertyBoolean("Open") == true) {
				If ($this->GetStatus() <> 102) {
					$this->SetStatus(102);
					$this->GetState();
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
	public function GetState()
	{
		If ($this->ReadPropertyBoolean("Open") == true) {
			$IP = $this->ReadPropertyString("IPAddress");
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://'.$IP.'/status');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$Result = curl_exec($ch);
			curl_close($ch);

			If ($Result === false) {
				$this->SendDebug("GetState", "Fehler beim Status-Update", 0);
			}
			else {
				$this->SendDebug("GetState", $Result, 0);
                    		$Data = json_decode($Result);
				$this->SetValue("xclk", $Data->{'xclk'});
				$this->SetValue("framesize", $Data->{'framesize'});
                    		$this->SetValue("quality", $Data->{'quality'});
				$this->SetValue("brightness", $Data->{'brightness'});
				$this->SetValue("contrast", $Data->{'contrast'});
				$this->SetValue("saturation", $Data->{'saturation'});
				$this->SetValue("special_effect", $Data->{'special_effect'});
				$this->SetValue("awb", $Data->{'awb'});
				$this->SetValue("awb_gain", $Data->{'awb_gain'});
				$this->SetValue("wb_mode", $Data->{'wb_mode'});
				$this->SetValue("aec", $Data->{'aec'});
				$this->SetValue("aec2", $Data->{'aec2'});
				$this->SetValue("ae_level", $Data->{'ae_level'});
				$this->SetValue("agc", $Data->{'agc'});
				$this->SetValue("gainceiling", $Data->{'gainceiling'});
				$this->SetValue("bpc", $Data->{'bpc'});
				$this->SetValue("wpc", $Data->{'wpc'});
				$this->SetValue("raw_gma", $Data->{'raw_gma'});
				$this->SetValue("lenc", $Data->{'lenc'});
				$this->SetValue("hmirror", $Data->{'hmirror'});
				$this->SetValue("dcw", $Data->{'dcw'});
				$this->SetValue("colorbar", $Data->{'colorbar'});
				$this->SetValue("led_intensity", $Data->{'led_intensity'});
			}	
		}
	}

	public function GetCapture()
	{
		If ($this->ReadPropertyBoolean("Open") == true) {
			$IP = $this->ReadPropertyString("IPAddress");
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://'.$IP.'/capture');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$Content = curl_exec($ch);
			curl_close($ch);
			
			IPS_SetMediaContent($this->GetIDForIdent("Capture_".$this->InstanceID), base64_encode($Content));  //Bild Base64 codieren und ablegen
			IPS_SendMediaEvent($this->GetIDForIdent("Capture_".$this->InstanceID)); //aktualisieren
		}
	} 

	public function StartStream()
	{
		If ($this->ReadPropertyBoolean("Open") == true) {
			$IP = $this->ReadPropertyString("IPAddress");
			$this->SetValue("Stream", '<img src="http://'.$IP.':81/stream">');
		}
	} 

	public function StopStream()
	{
		If ($this->ReadPropertyBoolean("Open") == true) {
			$this->SetValue("Stream", "");
		}
	} 
	    
	private function RegisterMediaObject($Name, $Ident, $Typ, $Parent, $Position, $Cached, $Filename)
	{
		$MediaID = @$this->GetIDForIdent($Ident);
		if($MediaID === false) {
		    	$MediaID = 0;
		}
		
		if ($MediaID == 0) {
			 // Image im MedienPool anlegen
			$MediaID = IPS_CreateMedia($Typ); 
			// Medienobjekt einsortieren unter Kategorie $catid
			IPS_SetParent($MediaID, $Parent);
			IPS_SetIdent($MediaID, $Ident);
			IPS_SetName($MediaID, $Name);
			IPS_SetPosition($MediaID, $Position);
                    	IPS_SetMediaCached($MediaID, $Cached);
			$ImageFile = IPS_GetKernelDir()."media".DIRECTORY_SEPARATOR.$Filename;  // Image-Datei
			IPS_SetMediaFile($MediaID, $ImageFile, false);    // Image im MedienPool mit Image-Datei verbinden
		}  
	}     
	    
	private function RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize)
	{
	        if (!IPS_VariableProfileExists($Name))
	        {
	            IPS_CreateVariableProfile($Name, 1);
	        }
	        else
	        {
	            $profile = IPS_GetVariableProfile($Name);
	            if ($profile['ProfileType'] != 1)
	                throw new Exception("Variable profile type does not match for profile " . $Name);
	        }
	        IPS_SetVariableProfileIcon($Name, $Icon);
	        IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
	        IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);    
	}    

}
?>
