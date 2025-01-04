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
		$this->RegisterPropertyInteger("CamType", 0);
		$this->RegisterTimer("ConnectionTest", 0, 'ESP32Cam_GetState($_IPS["TARGET"]);');
		$PreferenceArray = array();
		$PreferenceArray = array("framesize" => 8, 
					"quality" => 10, 
					"brightness" => 0, 
					"contrast" => 0, 
					"saturation" => 0,
					"special_effect" => 0,
					"awb" => 0,
					"awb_gain" => 0,
					"wb_mode" => 0,
					"aec" => 1,
					"aec2" => 0,
					"ae_level" => 0,
					"agc" => 1,
					"gainceiling" => 0,
					"bpc" => 0,
					"wpc" => 1,
					"raw_gma" => 1,
					"lenc" => 1,
					"hmirror" => 0,
					"vflip" => 0,
					"dcw" => 1,
					"colorbar" => 0,
					"led_intensity" => 0);
		
		$this->RegisterAttributeString("Preference", serialize($PreferenceArray)); 
		$this->RegisterPropertyBoolean("PreferenceReloadAfterStart", false);
		$this->RegisterPropertyBoolean("PreferenceReloadAfterOffline", false);

        }
 	
	public function GetConfigurationForm() 
	{ 
		$arrayStatus = array(); 
		$arrayStatus[] = array("code" => 101, "icon" => "inactive", "caption" => "Instanz wird erstellt"); 
		$arrayStatus[] = array("code" => 102, "icon" => "active", "caption" => "Instanz ist aktiv");
		$arrayStatus[] = array("code" => 104, "icon" => "inactive", "caption" => "Instanz ist inaktiv");
		$arrayStatus[] = array("code" => 202, "icon" => "error", "caption" => "Kommunikationfehler!");

				
		$arrayElements = array(); 
		$arrayElements[] = array("name" => "Open", "type" => "CheckBox",  "caption" => "Aktiv"); 
		$arrayElements[] = array("type" => "ValidationTextBox", "name" => "IPAddress", "caption" => "IP");

		$arrayOptions = array();
		$arrayOptions[] = array("label" => "Unbekannt", "value" => 0);
		$arrayOptions[] = array("label" => "OV2640", "value" => 1);
		$arrayOptions[] = array("label" => "OV3660", "value" => 1);
		$arrayElements[] = array("type" => "Select", "name" => "CamType", "caption" => "Kamera Typ", "options" => $arrayOptions);

 		$arrayElements[] = array("name" => "PreferenceReloadAfterStart", "type" => "CheckBox",  "caption" => "Einstellungen nach Restart laden"); 
		$arrayElements[] = array("name" => "PreferenceReloadAfterOffline", "type" => "CheckBox",  "caption" => "Einstellungen nach Offline laden"); 
		
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
		$this->RegisterProfileInteger("ESP32Cam.State", "Network", "", "", 0, 3, 0);
		IPS_SetVariableProfileAssociation("ESP32Cam.State", 0, "Online", "Network", 0x00FF00);
		IPS_SetVariableProfileAssociation("ESP32Cam.State", 1, "Offline", "Network", 0xFF0000);
		IPS_SetVariableProfileAssociation("ESP32Cam.State", 2, "Unbekannt", "Network", 0xFF0000);
		
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

		$this->RegisterProfileInteger("ESP32Cam.Quality", "Image", "", "", 10, 63, 1);
		$this->RegisterProfileInteger("ESP32Cam.QualityOV2640", "Image", "", "", 4, 63, 1);
		$this->RegisterProfileInteger("ESP32Cam.QualityOV3660", "Image", "", "", 4, 10, 1);

		$this->RegisterProfileInteger("ESP32Cam.Brightness", "Image", "", "", -2, 2, 1);
		$this->RegisterProfileInteger("ESP32Cam.BrightnessOV3660", "Image", "", "", -3, 3, 1);

		$this->RegisterProfileInteger("ESP32Cam.Contrast", "Image", "", "", -2, 2, 1);
		$this->RegisterProfileInteger("ESP32Cam.ContrastOV3660", "Image", "", "", -3, 3, 1);

		$this->RegisterProfileInteger("ESP32Cam.Saturation", "Image", "", "", -2, 2, 1);
		$this->RegisterProfileInteger("ESP32Cam.SaturationOV3660", "Image", "", "", -4, 4, 1);

		$this->RegisterProfileInteger("ESP32Cam.AELevel", "Image", "", "", -2, 2, 1);

		$this->RegisterProfileInteger("ESP32Cam.GainCeiling", "Image", "", "", 0, 6, 1);
		$this->RegisterProfileInteger("ESP32Cam.GainCeilingOV2640", "Image", "", "", 2, 128, 1);
		$this->RegisterProfileInteger("ESP32Cam.GainCeilingOV3660", "Image", "", "", 0, 511, 1);
		
		// Statusvariablen
		$this->RegisterVariableInteger("LastUpdate", "Letztes Update", "~UnixTimestamp", 10);
		
		$this->RegisterVariableInteger("State", "Status", "ESP32Cam.State", 20);

		$this->RegisterVariableBoolean("GetCapture", "Bild erstellen", "~Switch", 30);
		$this->EnableAction("GetCapture");

		$this->RegisterVariableBoolean("GetStream", "Stream", "~Switch", 40);
		$this->EnableAction("GetStream");

		$this->RegisterVariableBoolean("SetPreference", "Einstellungen sichern", "~Switch", 50);
		$this->EnableAction("SetPreference");

		$this->RegisterVariableBoolean("GetPreference", "Einstellungen wiederherstellen", "~Switch", 60);
		$this->EnableAction("GetPreference");
		
		$this->RegisterVariableInteger("xclk", "XCLK MHz", "", 70);
		$this->EnableAction("xclk");
		
		$this->RegisterVariableInteger("framesize", "Framesize", "ESP32Cam.Framesize", 80);
		$this->EnableAction("framesize");

		If ($this->ReadPropertyInteger("CamType") == 0) {
			$this->RegisterVariableInteger("quality", "Quality", "ESP32Cam.Quality", 90);
		} elseif ($this->ReadPropertyInteger("CamType") == 1) {
			$this->RegisterVariableInteger("quality", "Quality", "ESP32Cam.QualityOV2640", 90);
		} elseif ($this->ReadPropertyInteger("CamType") == 2) {
			$this->RegisterVariableInteger("quality", "Quality", "ESP32Cam.QualityOV3660", 90);
		}
		$this->EnableAction("quality");

		If ($this->ReadPropertyInteger("CamType") == 0) {
			$this->RegisterVariableInteger("brightness", "Brightness", "ESP32Cam.Brightness", 100);
		} elseif ($this->ReadPropertyInteger("CamType") == 1) {
			$this->RegisterVariableInteger("brightness", "Brightness", "ESP32Cam.Brightness", 100);
		} elseif ($this->ReadPropertyInteger("CamType") == 2) {
			$this->RegisterVariableInteger("brightness", "Brightness", "ESP32Cam.BrightnessOV3660", 100);
		}
		$this->EnableAction("brightness");

		If ($this->ReadPropertyInteger("CamType") == 0) {
			$this->RegisterVariableInteger("contrast", "Contrast", "ESP32Cam.Contrast", 110);
		} elseif ($this->ReadPropertyInteger("CamType") == 1) {
			$this->RegisterVariableInteger("contrast", "Contrast", "ESP32Cam.Contrast", 110);
		} elseif ($this->ReadPropertyInteger("CamType") == 2) {
			$this->RegisterVariableInteger("contrast", "Contrast", "ESP32Cam.ContrastOV3660", 110);
		}
		$this->EnableAction("contrast");

		If ($this->ReadPropertyInteger("CamType") == 0) {
			$this->RegisterVariableInteger("saturation", "Saturation", "ESP32Cam.Saturation", 120);
		} elseif ($this->ReadPropertyInteger("CamType") == 1) {
			$this->RegisterVariableInteger("saturation", "Saturation", "ESP32Cam.Saturation", 120);
		} elseif ($this->ReadPropertyInteger("CamType") == 2) {
			$this->RegisterVariableInteger("saturation", "Saturation", "SaturationOV3660", 120);
		}
		$this->EnableAction("saturation");

		$this->RegisterVariableInteger("special_effect", "Special Effect", "ESP32Cam.SpecialEffect", 130);
		$this->EnableAction("special_effect");

		$this->RegisterVariableBoolean("awb", "AWB", "~Switch", 140);
		$this->EnableAction("awb");
    
    		$this->RegisterVariableBoolean("awb_gain", "AWB Gain", "~Switch", 150);
		$this->EnableAction("awb_gain");

		$this->RegisterVariableInteger("wb_mode", "WB Mode", "ESP32Cam.WBMode", 160);
		$this->EnableAction("wb_mode");

		$this->RegisterVariableBoolean("aec", "AEC Sensor", "~Switch", 170);
		$this->EnableAction("aec");

		$this->RegisterVariableBoolean("aec2", "AEC DSP", "~Switch", 180);
		$this->EnableAction("aec2");

		$this->RegisterVariableInteger("ae_level", "AE Level", "ESP32Cam.AELevel", 190);
		$this->EnableAction("ae_level");

		$this->RegisterVariableBoolean("agc", "AGC", "~Switch", 200);
		$this->EnableAction("agc");

		If ($this->ReadPropertyInteger("CamType") == 0) {
			$this->RegisterVariableInteger("gainceiling", "Gain Ceiling", "ESP32Cam.GainCeiling", 210);
		} elseif ($this->ReadPropertyInteger("CamType") == 1) {
			$this->RegisterVariableInteger("gainceiling", "Gain Ceiling", "ESP32Cam.GainCeilingOV2640", 210);
		} elseif ($this->ReadPropertyInteger("CamType") == 2) {
			$this->RegisterVariableInteger("gainceiling", "Gain Ceiling", "ESP32Cam.GainCeilingOV3660", 210);
		}
		$this->EnableAction("gainceiling");

		$this->RegisterVariableBoolean("bpc", "BPC", "~Switch", 220);
		$this->EnableAction("bpc");
		
    		$this->RegisterVariableBoolean("wpc", "WPC", "~Switch", 230);
		$this->EnableAction("wpc");
		
		$this->RegisterVariableBoolean("raw_gma", "Raw GMA", "~Switch", 240);
		$this->EnableAction("raw_gma");

		$this->RegisterVariableBoolean("lenc", "Lens Correction", "~Switch", 250);
		$this->EnableAction("lenc");

		$this->RegisterVariableBoolean("hmirror", "H-Mirror", "~Switch", 260);
		$this->EnableAction("hmirror");

		$this->RegisterVariableBoolean("vflip", "V-Flip", "~Switch", 270);
		$this->EnableAction("vflip");
		
		$this->RegisterVariableBoolean("dcw", "DCW (Downsize EN)", "~Switch", 280);
		$this->EnableAction("dcw");
		
		$this->RegisterVariableBoolean("colorbar", "Color Bar", "~Switch", 290);
		$this->EnableAction("colorbar");

		$this->RegisterVariableInteger("led_intensity", "LED Intensity", "~Intensity.255", 300);
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
		
    
		If (($this->ReadPropertyBoolean("Open") == true) AND ($this->ConnectionTest() == true)) {
			If ($this->GetStatus() <> 102) {
				$this->SetStatus(102);
			}
			$this->GetState();
			If ($this->ReadPropertyBoolean("PreferenceReloadAfterStart") == true)  {
				$this->GetPreference();
			}
			$this->SetTimerInterval("ConnectionTest", 30 * 1000);
		}
		elseif (($this->ReadPropertyBoolean("Open") == true) AND ($this->ConnectionTest() == false)) {
			If ($this->GetStatus() <> 202) {
				$this->SetStatus(202);
			}
			$this->GetState();
			$this->SetTimerInterval("ConnectionTest", 30 * 1000);
		}
		else {
			If ($this->GetStatus() <> 104) {
				$this->SetStatus(104);
			}
			$this->SetTimerInterval("ConnectionTest", 0);
		}
		
	}

	public function RequestAction($Ident, $Value) 
	{
		switch($Ident) {
		case "GetCapture":
			$this->SetValue($Ident, true);
			$this->GetCapture();
			$this->SetValue($Ident, false);
			break;
		case "GetStream":
			If ($Value == true) {
				$this->StartStream();
				$this->SetValue($Ident, true);
			}
			else {
				$this->StopStream();
				$this->SetValue($Ident, false);
			}
			break;
		case "SetPreference":
			$this->SetValue($Ident, true);
			$this->SetPreference();
			$this->SetValue($Ident, false);
			break;
		case "GetPreference":
			$this->SetValue($Ident, true);
			$this->GetPreference();
			$this->SetValue($Ident, false);
			break;
		case "framesize":
			$this->SetState("framesize", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "quality":
			$this->SetState("quality", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "brightness":
			$this->SetState("brightness", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "contrast":
			$this->SetState("contrast", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "saturation":
			$this->SetState("saturation", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "special_effect":
			$this->SetState("special_effect", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "awb":
			$this->SetState("awb", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "awb_gain":
			$this->SetState("awb_gain", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "wb_mode":
			$this->SetState("wb_mode", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "aec":
			$this->SetState("aec", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "aec2":
			$this->SetState("aec2", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "ae_level":
			$this->SetState("ae_level", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "agc":
			$this->SetState("agc", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "gainceiling":
			$this->SetState("gainceiling", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "bpc":
			$this->SetState("bpc", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "wpc":
			$this->SetState("wpc", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "raw_gma":
			$this->SetState("raw_gma", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "lenc":
			$this->SetState("lenc", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "hmirror":
			$this->SetState("hmirror", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "vflip":
			$this->SetState("vflip", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "dcw":
			$this->SetState("dcw", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "colorbar":
			$this->SetState("colorbar", $Value);
			$this->SetValue($Ident, $Value);
			break;
		case "led_intensity":
			$this->SetState("led_intensity", $Value);
			$this->SetValue($Ident, $Value);
			break;
			
		default:
		    throw new Exception("Invalid Ident");
		}
	}
	    
	// Beginn der Funktionen
	public function GetState()
	{
		If (($this->ReadPropertyBoolean("Open") == true) AND ($this->ConnectionTest() == true)) {
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
				$this->SetValueWhenChanged("xclk", $Data->{'xclk'});
				$this->SetValueWhenChanged("framesize", $Data->{'framesize'});
                    		$this->SetValueWhenChanged("quality", $Data->{'quality'});
				$this->SetValueWhenChanged("brightness", $Data->{'brightness'});
				$this->SetValueWhenChanged("contrast", $Data->{'contrast'});
				$this->SetValueWhenChanged("saturation", $Data->{'saturation'});
				$this->SetValueWhenChanged("special_effect", $Data->{'special_effect'});
				$this->SetValueWhenChanged("awb", $Data->{'awb'});
				$this->SetValueWhenChanged("awb_gain", $Data->{'awb_gain'});
				$this->SetValueWhenChanged("wb_mode", $Data->{'wb_mode'});
				$this->SetValueWhenChanged("aec", $Data->{'aec'});
				$this->SetValueWhenChanged("aec2", $Data->{'aec2'});
				$this->SetValueWhenChanged("ae_level", $Data->{'ae_level'});
				$this->SetValueWhenChanged("agc", $Data->{'agc'});
				$this->SetValueWhenChanged("gainceiling", $Data->{'gainceiling'});
				$this->SetValueWhenChanged("bpc", $Data->{'bpc'});
				$this->SetValueWhenChanged("wpc", $Data->{'wpc'});
				$this->SetValueWhenChanged("raw_gma", $Data->{'raw_gma'});
				$this->SetValueWhenChanged("lenc", $Data->{'lenc'});
				$this->SetValueWhenChanged("hmirror", $Data->{'hmirror'});
				If (isset($Data->{'vflip'})) { // Die Variable wird nicht immer mitgeliefert
					$this->SetValueWhenChanged("vflip", $Data->{'vflip'});
				}
				$this->SetValueWhenChanged("dcw", $Data->{'dcw'});
				$this->SetValueWhenChanged("colorbar", $Data->{'colorbar'});
				$this->SetValueWhenChanged("led_intensity", $Data->{'led_intensity'});

				$this->SetValueWhenChanged("LastUpdate", time() );
			}	
		}
	}

	public function SetState(String $Variable, int $Value)
	{
		If (($this->ReadPropertyBoolean("Open") == true) AND ($this->ConnectionTest() == true)) {
			$IP = $this->ReadPropertyString("IPAddress");
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://'.$IP.'/control?var='.$Variable.'&val='.$Value);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$Result = curl_exec($ch);
			curl_close($ch);
			
			If ($Result === false) {
				$this->SendDebug("SetState", "Fehler beim Status-Update", 0);
			}
			$this->GetState();
		}
	} 

	    
	public function GetCapture()
	{
		If (($this->ReadPropertyBoolean("Open") == true) AND ($this->ConnectionTest() == true)) {
			$IP = $this->ReadPropertyString("IPAddress");
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://'.$IP.'/capture');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$Content = curl_exec($ch);
			curl_close($ch);
			
			IPS_SetMediaContent($this->GetIDForIdent("Capture_".$this->InstanceID), base64_encode($Content));  //Bild Base64 codieren und ablegen
			IPS_SendMediaEvent($this->GetIDForIdent("Capture_".$this->InstanceID)); //aktualisieren

			$this->GetState();
		}
	} 

	public function StartStream()
	{
		If (($this->ReadPropertyBoolean("Open") == true) AND ($this->ConnectionTest() == true)) {
			$this->SendDebug("StartStream", "Ausfuehrung", 0);
			$IP = $this->ReadPropertyString("IPAddress");
			$this->SetValue("Stream", '<img src="http://'.$IP.':81/stream">');

			$this->GetState();
		}
	} 

	public function StopStream()
	{
		If (($this->ReadPropertyBoolean("Open") == true) AND ($this->ConnectionTest() == true)) {
			$this->SendDebug("StopStream", "Ausfuehrung", 0);
			$this->SetValue("Stream", "");

			$this->GetState();
		}
	} 

	public function SetPreference()
	{
		If (($this->ReadPropertyBoolean("Open") == true) AND ($this->ConnectionTest() == true)) {
			$this->SendDebug("SetPreference", "Ausfuehrung", 0);
			$PreferenceArray = array();
			$PreferenceArray = array("framesize" => $this->GetValue("framesize"), 
						"quality" => $this->GetValue("quality"), 
						"brightness" => $this->GetValue("brightness"), 
						"contrast" => $this->GetValue("contrast"), 
						"saturation" => $this->GetValue("saturation"),
						"special_effect" => $this->GetValue("special_effect"),
						"awb" => $this->GetValue("awb"),
						"awb_gain" => $this->GetValue("awb_gain"),
						"wb_mode" => $this->GetValue("wb_mode"),
						"aec" => $this->GetValue("aec"),
						"aec2" => $this->GetValue("aec2"),
						"ae_level" => $this->GetValue("ae_level"),
						"agc" => $this->GetValue("agc"),
						"gainceiling" => $this->GetValue("gainceiling"),
						"bpc" => $this->GetValue("bpc"),
						"wpc" => $this->GetValue("wpc"),
						"raw_gma" => $this->GetValue("raw_gma"),
						"lenc" => $this->GetValue("lenc"),
						"hmirror" => $this->GetValue("hmirror"),
						"vflip" => $this->GetValue("vflip"),
						"dcw" => $this->GetValue("dcw"),
						"colorbar" => $this->GetValue("colorbar"),
						"led_intensity" => $this->GetValue("led_intensity"));
			$this->SendDebug("SetPreference", serialize($PreferenceArray), 0);
			$this->WriteAttributeString("Preference", serialize($PreferenceArray));
		}
	} 

	public function GetPreference()
	{
		If (($this->ReadPropertyBoolean("Open") == true) AND ($this->ConnectionTest() == true)) {
			$this->SendDebug("GetPreference", "Ausfuehrung", 0);
			$PreferenceArray = array();
			$PreferenceArray = unserialize($this->ReadAttributeString("Preference"));
			//$this->SendDebug("GetPreference", "Framesize: ".$PreferenceArray["framesize"], 0);
			
			$this->RequestActionWhenChanged("framesize", $PreferenceArray["framesize"]);
			$this->RequestActionWhenChanged("quality", $PreferenceArray["quality"]);
			$this->RequestActionWhenChanged("brightness", $PreferenceArray["brightness"]);
			$this->RequestActionWhenChanged("contrast", $PreferenceArray["contrast"]);
			$this->RequestActionWhenChanged("saturation", $PreferenceArray["saturation"]);
			$this->RequestActionWhenChanged("special_effect", $PreferenceArray["special_effect"]);
			$this->RequestActionWhenChanged("awb", $PreferenceArray["awb"]);
			$this->RequestActionWhenChanged("awb_gain", $PreferenceArray["awb_gain"]);
			$this->RequestActionWhenChanged("wb_mode", $PreferenceArray["wb_mode"]);
			$this->RequestActionWhenChanged("aec", $PreferenceArray["aec"]);
			$this->RequestActionWhenChanged("aec2", $PreferenceArray["aec2"]);
			$this->RequestActionWhenChanged("ae_level", $PreferenceArray["ae_level"]);
			$this->RequestActionWhenChanged("agc", $PreferenceArray["agc"]);
			$this->RequestActionWhenChanged("gainceiling", $PreferenceArray["gainceiling"]);
			$this->RequestActionWhenChanged("bpc", $PreferenceArray["bpc"]);
			$this->RequestActionWhenChanged("wpc", $PreferenceArray["wpc"]);
			$this->RequestActionWhenChanged("raw_gma", $PreferenceArray["raw_gma"]);
			$this->RequestActionWhenChanged("lenc", $PreferenceArray["lenc"]);
			$this->RequestActionWhenChanged("hmirror", $PreferenceArray["hmirror"]);
			$this->RequestActionWhenChanged("vflip", $PreferenceArray["vflip"]);
			$this->RequestActionWhenChanged("dcw", $PreferenceArray["dcw"]);
			$this->RequestActionWhenChanged("colorbar", $PreferenceArray["colorbar"]);
			$this->RequestActionWhenChanged("led_intensity", $PreferenceArray["led_intensity"]);
			
		}
	} 
	    
	public function ConnectionTest()
	{
	      $result = false;
	      If (Sys_Ping($this->ReadPropertyString("IPAddress"), 1000)) {
			If ($this->GetStatus() <> 102) {
				$this->SetStatus(102);
				If ($this->ReadPropertyBoolean("PreferenceReloadAfterOffline") == true)  {
					$this->GetPreference();
				}
			}
		      	$result = true;
		      	$this->SetValue("State", 0);
		}
		else {
			IPS_LogMessage("ESP32Cam","IP ".$this->ReadPropertyString("IPAddress")." reagiert nicht!");
			$this->SendDebug("ConnectionTest", "IP ".$this->ReadPropertyString("IPAddress")." reagiert nicht!", 0);
			$this->SetValue("State", 1);
			If ($this->GetStatus() <> 202) {
				$this->SetStatus(202);
			}
		}
	return $result;
	}

	private function RequestActionWhenChanged($Ident, $Value)
    	{
        	if ($this->GetValue($Ident) != $Value) {
            		$this->RequestAction($Ident, $Value);
			$this->SendDebug("RequestActionWhenChanged", "Variable ".$Ident." wurde auf Wert ".$Value." gesetzt", 0);
        	}
    	}    
	    
	private function SetValueWhenChanged($Ident, $Value)
    	{
        	if ($this->GetValue($Ident) != $Value) {
            		$this->SetValue($Ident, $Value);
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
