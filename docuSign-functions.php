<?php
/****************************************************************************************
***** DocuSign functions  ******************************************************************
***********************************************************************************************
Functions:
1) Create and Send Envelopes
2) Get Status from docs and show them under ManageRIA Docs
3) Connect DS CurlAPI
4) Create XML Envelope
Developer: Gisela Alessandrello
Last modified: 20 September 2016
*********************************************************************************************
*********************************************************************************************/



function ConnectDSCurlApi() {
	
	chdir(dirname(__FILE__));
	include ('../../../keys/docusign_keys.php');
	
	// construct the authentication header:
	$header = "<DocuSignCredentials><Username>" . Docu_username . "</Username><Password>" .  Docu_password . "</Password><IntegratorKey>" . Docu_integrator_key . "</IntegratorKey></DocuSignCredentials>";

	$curl = curl_init(Docu_host_loginUrl);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-DocuSign-Authentication: $header"));

	$json_response = curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	if ( $status != 200 ) {
		return (['ok' => false, 'errMsg' => "Error calling DocuSign, status is: " . $status]);
	}

	$response = json_decode($json_response, true);
	$accountId = $response["loginAccounts"][0]["accountId"];
	$baseUrl = $response["loginAccounts"][0]["baseUrl"];
	
	$returnArr = [
		"header" => $header,
		"accountId" => $accountId,
		"baseUrl" => $baseUrl
	];
	
	curl_close($curl);	
	return $returnArr;
	
}




//***************************************************
//create XML document from Process and user variables
//this XML gets used by CreateAndSendXMLEnvelope method
//***************************************************
	
function MakeEnvelopeXML($con, $riaID, $docIDArray, $sendDateTimeStr, $selectedClientID, $selectedClientName, $selectedClientEmail, $selectedProcessName, $selectedProcessId, $emailSubj, $emailBlurb, $companyData) {
		
	include ('../../../keys/docusign_keys.php');
	
	//db query
	//make doc list for query
	$docListQ = "(";
	foreach ($docIDArray as &$value) {
		$docListQ .= $value . ",";
	}
	unset($value);
	//remove last comma
	if(substr($docListQ,-1)==",")
		$docListQ = substr($docListQ,0,strlen($docListQ)-1);
	$docListQ .= ")";

	//do query
	$processDocsQuery = "select aa.doc_id, aa.name_display, aa.path_internal, aa.pdf_bytes, bb.field_id,  cc.tag_type, cc.autofill_yes_no, cc.autofill_string, cc.SQL_get_data, bb.page_number, bb.x_location, bb.y_location ";
	$processDocsQuery .= "from doc_header aa ";
	$processDocsQuery .= "left join doc_fields_line bb on aa.doc_id=bb.doc_id "; 
	$processDocsQuery .= "left join doc_fields_header cc on bb.field_id=cc.field_id ";
	$processDocsQuery .= "where aa.doc_id in " . $docListQ;
	$processDocsQuery .= " order by doc_id";

	$processDocsResponse = mysqli_query($con,$processDocsQuery);

	//make documents json to use in xml
	$procDocs_arr = array();
	$procDocs_tagArr = array();
	$currDocID = 0;
	$procDocLoopCount = 0;

	//************************************************
	//Here we create a Json array of unique Documents
	//with arrays of Tabs belonging to them
	//************************************************
	while ($processDocsRow = mysqli_fetch_assoc($processDocsResponse)) {

		if ($processDocsRow['doc_id'] == $currDocID) {
			//same doc id, so push new tag_array
			$tag_array = array();
			$tag_array['field_id'] = $processDocsRow['field_id'];
			$tag_array['tag_type'] = $processDocsRow['tag_type'];
			$tag_array['autofill_yes_no'] = $processDocsRow['autofill_yes_no'];
			$tag_array['autofill_string'] = $processDocsRow['autofill_string'];
			$tag_array['page_number'] = $processDocsRow['page_number'];
			$tag_array['x_location'] = $processDocsRow['x_location'];
			$tag_array['y_location'] = $processDocsRow['y_location'];
			$tag_array['custom_val'] = "";
			//allow for custom company tag
			if ($processDocsRow['tag_type'] == "Company") {
				$tag_array['custom_val'] = $companyData;
			}
			if ($processDocsRow['tag_type'] == "TDRepCodes") {
				$tag_array['custom_val'] = GetTDRepCodes($con, $riaID);
			}
			if ($processDocsRow['tag_type'] == "FirmCRDCode") {
				$tag_array['custom_val'] = GetCRDcode($con, $riaID);
			}
			array_push($procDocs_tagArr,$tag_array);
		} else {
			//new doc id - wrap up last tag_array
			if ($currDocID != 0) {
				$row_array['tags'] = $procDocs_tagArr;
				array_push($procDocs_arr,$row_array);
			};
			$procDocs_tagArr = array();
			$tag_array = array();
			$row_array['id'] = $processDocsRow['doc_id'];
			$row_array['name'] = $processDocsRow['name_display'];
			$row_array['bytes'] = $processDocsRow['pdf_bytes'];
			$tag_array['field_id'] = $processDocsRow['field_id'];
			$tag_array['tag_type'] = $processDocsRow['tag_type'];
			$tag_array['autofill_yes_no'] = $processDocsRow['autofill_yes_no'];
			$tag_array['autofill_string'] = $processDocsRow['autofill_string'];
			$tag_array['page_number'] = $processDocsRow['page_number'];
			$tag_array['x_location'] = $processDocsRow['x_location'];
			$tag_array['y_location'] = $processDocsRow['y_location'];
			$tag_array['custom_val'] = "";
			if ($processDocsRow['tag_type'] == "Company") {
				$tag_array['custom_val'] = $companyData;
			}
			if ($processDocsRow['tag_type'] == "TDRepCodes") {
				$tag_array['custom_val'] = GetTDRepCodes($con, $riaID);
			}
			if ($processDocsRow['tag_type'] == "FirmCRDCode") {
				$tag_array['custom_val'] = GetCRDcode($con, $riaID);
			}
			array_push($procDocs_tagArr,$tag_array);

		};
		//if last row then save
		if ($procDocLoopCount == mysqli_num_rows($processDocsResponse) - 1) {
			$row_array['tags'] = $procDocs_tagArr;
			array_push($procDocs_arr,$row_array);
		};
		
		$procDocLoopCount++;
		$currDocID = $processDocsRow['doc_id'];

	};
	
	//return json_encode($procDocs_arr);

	//************************************************
	//build DocuSign xml modeled from TD xml docs
	//************************************************

	//EnvelopeTemplate root
	$docuEnvXML = new SimpleXMLElement("<EnvelopeTemplate></EnvelopeTemplate>");
	$docuEnvXML->addAttribute('xmlns', 'http://www.docusign.net/API/3.0');
	$docuEnvXML->addAttribute('xmlns:xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
	$docuEnvXML->addAttribute('xmlns:xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
	//EnvelopeTemplateDefinition element
	$docuEnvTemplDef = $docuEnvXML->addChild('EnvelopeTemplateDefinition');
	$docuEnvTemplDef->addChild('TemplateID', Docu_integrator_key);
	$docuEnvTemplDef->addChild('Name', $selectedProcessName);
	$docuEnvTemplDef->addChild('Shared', 'true');
	$docuEnvTemplDef->addChild('TemplatePassword', Docu_password);
	$docuEnvTemplDef->addChild('TemplateDescription', '');
	$docuEnvTemplDef->addChild('PageCount', '1');
	//Envelope element
	$docuEnvEnv = $docuEnvXML->addChild('Envelope');
	$docuEnvEnv->addChild('AccountId', $integrator_key);
	$docuEnvEnvDocs = $docuEnvEnv->addChild('Documents');
	foreach($procDocs_arr as $val) {
		$documentNode = $docuEnvEnvDocs->addChild('Document');
		$documentNode->addChild('ID', $val['id']);
		$documentNode->addChild('Name', $val['name']);
		$documentNode->addChild('PDFBytes', $val['bytes']);
		$documentNode->addChild('FileExtension', 'pdf');
	};
	unset($val);
	//Recipients Element
	$docuEnvEnvRecips = $docuEnvEnv->addChild('Recipients');
	$docuEnvEnvRecip = $docuEnvEnvRecips->addChild('Recipient');
	$docuEnvEnvRecip->addChild('ID', $selectedClientID);
	$docuEnvEnvRecip->addChild('UserName', ltrim($selectedClientName));
	$docuEnvEnvRecip->addChild('Email', $selectedClientEmail);
	$docuEnvEnvRecip->addChild('Type', 'Signer');
	$docuEnvEnvRecip->addChild('RoleName', 'Client');
	$docuEnvEnvRecip->addChild('TemplateLocked', 'false');
	$docuEnvEnvRecip->addChild('TemplateRequired', 'false');
	$docuEnvEnvRecip->addChild('ExcludedDocuments', '');
	//Tabs
	$docuEnvEnvTabs = $docuEnvEnv->addChild('Tabs');
	foreach($procDocs_arr as $val) {
		$thisTabDocId = $val['id'];
		$docTagArray = $val['tags'];
		foreach($docTagArray as $tagVal) {
			$tabNode = $docuEnvEnvTabs->addChild('Tab');
			$tabNode->addChild('DocumentID', $thisTabDocId);
			$tabNode->addChild('PageNumber', $tagVal['page_number']);
			$tabNode->addChild('XPosition', $tagVal['x_location']);
			$tabNode->addChild('YPosition', $tagVal['y_location']);
			$tabNode->addChild('RecipientID', $selectedClientID);
			$tabNode->addChild('Type', $tagVal['tag_type']);
			$tabNode->addChild('Autofill', $tagVal['autofill_yes_no']);
			$tabNode->addChild('AutofillString', $tagVal['autofill_string']);
			$tabNode->addChild('CustomValue', $tagVal['custom_val']);			
		}
	};
	//Email Subject, Blurb
	$docuEnvEnvTabs = $docuEnvEnv->addChild('Subject', $emailSubj);
	$docuEnvEnvTabs = $docuEnvEnv->addChild('EmailBlurb', $emailBlurb);
	$docuEnvEnvTabs = $docuEnvEnv->addChild('SigningLocation', 'Online');
	
	return $docuEnvXML;
	
}


function CreateAndSendXMLEnvelope($XmlEnvelope) {
	

	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 1 - create and Json Envelope from XmlEnvelope
	/////////////////////////////////////////////////////////////////////////////////////////////////
	$envSubj =  $XmlEnvelope->xpath('/EnvelopeTemplate/Envelope/Subject');
	$envBlurb =  $XmlEnvelope->xpath('/EnvelopeTemplate/Envelope/EmailBlurb');

	//create json for documents
	$envDocumentsJson = array();
	$thisDocument = array();
	$docNodes =  $XmlEnvelope->xpath("/EnvelopeTemplate/Envelope/Documents/Document");
	foreach ($docNodes as $docNode) {
			
			$thisDocument['documentId'] = (string)$docNode->ID;
			$thisDocument['name'] = (string)$docNode->Name;
			$thisDocument['fileExtension'] = "pdf";
			$thisDocument['documentBase64'] = (string)$docNode->PDFBytes;	
			array_push($envDocumentsJson, $thisDocument);
			
	};
	
	//create json for recipients
	$envRecipJson = array();
	$envSignersJson = array();
	$thisSigner = array();

	$recipNodes =  $XmlEnvelope->xpath("/EnvelopeTemplate/Envelope/Recipients/Recipient");
	//$envRecipJson["signers"] = $thisSigner;
	foreach ($recipNodes as $recipNode) {
		

			$thisSigner = array();
			$recipTabArray = array();
			$thisSigner["name"] = (string)$recipNode->UserName;
			$thisSigner["email"] = (string)$recipNode->Email;
			$thisSigner["recipientId"] = (string)$recipNode->ID;
			$thisSigner["requireIdLookup"] = "false";
			//$thisSigner["clientUserId"] = (string)$recipNode->ID;
			$thisSigner["routingOrder"] = "1";
			
			$thisSigner["tabs"] = array();
			//begin add tabs to signer
			//selects regular tabs assigned to this recipient 
			$signerManualTabXpath = "//Tabs/Tab[RecipientID='" . (string)$recipNode->ID . "'][Autofill='0']";
			$manualTabsOfSigner =  $XmlEnvelope->xpath($signerManualTabXpath);
			//selects Auto tabs assigned to this recipient 
			$signerAutoTabXpath = "//Tabs/Tab[RecipientID='" . (string)$recipNode->ID . "'][Autofill='1'][Type[not(. = following::Type)]]";
			$autoTabsOfSigner =  $XmlEnvelope->xpath($signerAutoTabXpath);
			
			$recipTextTabArray = array();
			$recipFirstNameTabArray = array();
			$recipLastNameTabArray = array();
			$recipFullNameTabArray = array();
			$recipEmailAddrTabArray = array();
			$recipSignTabArray = array();
			$recipInitialTabArray = array();
			$dateSignedTabArray = array();
			
			foreach ($manualTabsOfSigner as $tabNode) {
				switch ($tabNode->Type) {
					case "Title":
						break;	
					case "FirstName":
						$tabArr = array();
						$tabArr = SetManualDocuTab($tabArr, $tabNode);
						array_push($recipFirstNameTabArray, $tabArr);
						break;
					case "LastName":
						$tabArr = array();
						$tabArr = SetManualDocuTab($tabArr, $tabNode);
						array_push($recipLastNameTabArray, $tabArr);
						break;
					case "FullName":
						$tabArr = array();
						$tabArr = SetManualDocuTab($tabArr, $tabNode);
						array_push($recipFullNameTabArray, $tabArr);
						break;
					case "EmailAddress":
					case "Email":
						$tabArr = array();
						$tabArr = SetManualDocuTab($tabArr, $tabNode);
						array_push($recipEmailAddrTabArray, $tabArr);
						break;
					case "SignHere":
						$tabArr = array();
						$tabArr = SetManualDocuTab($tabArr, $tabNode);
						array_push($recipSignTabArray, $tabArr);
						break;
					case "InitialHere":
						$tabArr = array();
						$tabArr = SetManualDocuTab($tabArr, $tabNode);
						array_push($recipInitialTabArray, $tabArr);
						break;
					case "Company":
					case "TDRepCodes":
					case "FirmCRDCode":
						$tabArr = array();
						$tabArr = SetManualDocuTab($tabArr, $tabNode);
						$tabArr["value"] = (string)$tabNode->CustomValue;
						$tabArr["locked"] = "true";
						array_push($recipTextTabArray, $tabArr);
						break;
					case "DateSigned":
						$tabArr = array();
						$tabArr = SetManualDocuTab($tabArr, $tabNode);
						array_push($dateSignedTabArray, $tabArr);
						break;
					case "Editable":
						$tabArr = array();
						$tabArr = SetManualDocuTab($tabArr, $tabNode);
						$tabArr["value"] = (string)$tabNode->CustomValue;
						$tabArr["locked"] = "false";
						$tabArr["width"] = "150";
						array_push($recipTextTabArray, $tabArr);
						break;

				}
			}

			
			foreach ($autoTabsOfSigner as $tabNode) {
				switch ($tabNode->Type) {
						
					case "FirstName":
						$tabArr = array();
						$tabArr = SetAutoDocuTab($tabArr, $tabNode);
						array_push($recipFirstNameTabArray, $tabArr);
						break;
					case "LastName":
						$tabArr = array();
						$tabArr = SetAutoDocuTab($tabArr, $tabNode);
						array_push($recipLastNameTabArray, $tabArr);
						break;
					case "FullName":
						$tabArr = array();
						$tabArr = SetAutoDocuTab($tabArr, $tabNode);
						array_push($recipFullNameTabArray, $tabArr);
						break;
					case "EmailAddress":
					case "Email":
						$tabArr = array();
						$tabArr = SetAutoDocuTab($tabArr, $tabNode);
						array_push($recipEmailAddrTabArray, $tabArr);
						break;
					case "SignHere":
						$tabArr = array();
						$tabArr = SetAutoDocuTab($tabArr, $tabNode);
						array_push($recipSignTabArray, $tabArr);
						break;
					case "InitialHere":
						$tabArr = array();
						$tabArr = SetAutoDocuTab($tabArr, $tabNode);
						array_push($recipInitialTabArray, $tabArr);
						break;
					case "Company":
					case "TDRepCodes":
					case "FirmCRDCode":
						$tabArr = array();
						$tabArr = SetAutoDocuTab($tabArr, $tabNode);
						$tabArr["value"] = (string)$tabNode->CustomValue;
						$tabArr["locked"] = "true";
						array_push($recipTextTabArray, $tabArr);
						break;
					case "DateSigned":
						$tabArr = array();
						$tabArr = SetAutoDocuTab($tabArr, $tabNode);
						array_push($dateSignedTabArray, $tabArr);
						break;
					case "Editable":
						$tabArr = array();
						$tabArr = SetAutoDocuTab($tabArr, $tabNode);
						$tabArr["value"] = (string)$tabNode->CustomValue;
						$tabArr["locked"] = "false";
						$tabArr["width"] = "150";
						array_push($recipTextTabArray, $tabArr);
						break;

				}
			}

			
			if (!empty($recipFirstNameTabArray)) $thisSigner["tabs"]["firstNameTabs"] = $recipFirstNameTabArray;
			if (!empty($recipLastNameTabArray)) $thisSigner["tabs"]["lastNameTabs"] = $recipLastNameTabArray;
			if (!empty($recipFullNameTabArray)) $thisSigner["tabs"]["fullNameTabs"] = $recipFullNameTabArray;
			//if (!empty($recipTitleTabArray[0])) array_push($thisSigner["tabs"], $recipTitleTabArray);
			if (!empty($recipEmailAddrTabArray)) $thisSigner["tabs"]["emailAddressTabs"] = $recipEmailAddrTabArray;
			if (!empty($recipSignTabArray)) $thisSigner["tabs"]["signHereTabs"] = $recipSignTabArray;
			if (!empty($recipInitialTabArray)) $thisSigner["tabs"]["initialHereTabs"] = $recipInitialTabArray;
			if (!empty($recipTextTabArray)) $thisSigner["tabs"]["textTabs"] = $recipTextTabArray;
			if (!empty($dateSignedTabArray)) $thisSigner["tabs"]["dateSignedTabs"] = $dateSignedTabArray;
			array_push($envSignersJson, $thisSigner);
			
	}
	$envRecipJson["signers"] = $envSignersJson;

	
	$DocuSignJson = [
		"status" => "sent",
		"emailSubject" => (string)$envSubj[0],
		"emailBlurb" => (string)$envBlurb[0],
		"documents" => $envDocumentsJson,
		"recipients" => $envRecipJson
	];
	
	//$returnArr = $DocuSignJson;
	

	$data_string = json_encode($DocuSignJson);

	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 2 - login to DocuSign API
	/////////////////////////////////////////////////////////////////////////////////////////////////

	$docuCurlApiJson = ConnectDSCurlApi();
	$header = $docuCurlApiJson["header"];
	$accountId = $docuCurlApiJson["accountId"];
	$baseUrl = $docuCurlApiJson["baseUrl"];	
	
	
	$requestBody = "\r\n\r\n--myboundary\r\nContent-Type: application/json\r\nContent-Disposition: form-data\r\n\r\n$data_string\r\n--myboundary--\r\n";
	
	
	$curl = curl_init($baseUrl . "/envelopes" );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);                                                                  
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: multipart/form-data;boundary=myboundary',
		'Content-Length: ' . strlen($requestBody),
		"X-DocuSign-Authentication: $header" )                                                                       
	);
	$json_response = curl_exec($curl); // Do it!
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
	$statusString = "SUCCESS!";
	
	if ( $status != 201 ) {
		//echo "Error calling DocuSign, status is:" . $status . "\nerror text: ";
		$statusString = $status;
		//print_r($json_response); echo "\n";
		//exit(-1);
	}
	$response = json_decode($json_response, true);
	
	if(array_key_exists('errorCode', $response)) {
		$statusString .= " " . $response["errorCode"] . ": " . $response["message"];
	}
	
	$envelopeId = $response["envelopeId"];
	
	
	$returnArr = [
		"envelopeId" => $envelopeId,
		"envelopeUri" => $baseUrl,
		"statusString" => $statusString
	];
	
	
	//$returnArr = $json_response;
	//$returnArr = $DocuSignJson;
	
	return $returnArr;
	
}



//************************************************************
// after creating an envelope, call this to store for tracking
// takes these parameters:
// $con - db connection
// $riaID - the RIA id
// $docIDArray - 1 dimensional array of DocuSign document ids
// $envelopeId - DocuSign envelope id returned from CreateAndSendXMLEnvelope()
// $envelopeUri - DocuSign envelope uri returned from CreateAndSendXMLEnvelope()
// $recipientRiaUserId - ria_user_id of recipient
// $recipientClientUserId - cl_user_id of recipient
// $recpientEmail - email of envelope reicipient
// $sentToRiaId - RIA id of recipient (maybe same maybe different)
// $sentByUserID - ria_user_id of sender (NULL if from RobustWealth)
// $senderEmail - email if user sending the envelope
// $processId - process_id from doc_process_header
// $sendDateTimeStr - current date as date('m/d/Y H:i A')
//************************************************************

function SaveEnvelopeForTracking($con, $riaID, $docIDArray, $envelopeId, $envelopeUri, $recipientRiaUserId, $recipientClientUserId, $recpientEmail, $sentToRiaId, $sentByUserID, $senderEmail, $processId, $sendDateTimeStr) {
	$docLineinsertSql = "";
	$returnCode = "";
	
	$insertRecipRiaUserIdStr = ($recipientRiaUserId == NULL)? 'NULL':"'" . $recipientRiaUserId . "'";
	$insertRecipientClientUserIdStr = ($recipientClientUserId == NULL)? 'NULL':"'" . $recipientClientUserId . "'";
	
	
	$docLineinsertSql .= "insert into doc_line (doc_header_id, docusign_env_id, docusign_url, sent_to_email, sent_to_ria_user_id, sent_to_cl_user_id, sent_by_email, sent_to_ria_id, sent_by_user_id, sent_by_ria_id, process_id, sent_date) values";
	foreach($docIDArray as $val) {
		$docLineinsertSql .= "(" . $val . ", '" . $envelopeId . "', '" . addslashes($envelopeUri) . "', '" . $recpientEmail . "', " . $insertRecipRiaUserIdStr . ", " . $insertRecipientClientUserIdStr . ", '" . $senderEmail . "', '" . $sentToRiaId . "', '" . $sentByUserID . "', '" . $riaID . "', " . $processId . ", NOW()),";
	};
	//remove last comma from docLineinsertSql
	if(substr($docLineinsertSql,-1)==",")
		$docLineinsertSql = substr($docLineinsertSql,0,strlen($docLineinsertSql)-1);
	unset($val);

	if ($insertResult=mysqli_query($con, $docLineinsertSql)) {
		$returnCode = "SUCCESS!";
	} else {
		$returnCode = "Error: " . mysqli_error($con);
	}
	mysqli_free_result($insertResult);

	return $returnCode;
}



//***************************************************
//Gets status for one envelope
//***************************************************

function GetEnvelopeStatus($envelopeId) {
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 1 - login to DocuSign API
	/////////////////////////////////////////////////////////////////////////////////////////////////
	$docuCurlApiJson = ConnectDSCurlApi();
	$header = $docuCurlApiJson["header"];
	$accountId = $docuCurlApiJson["accountId"];
	$baseUrl = $docuCurlApiJson["baseUrl"];	

	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 2 - retrieve envelope status
	/////////////////////////////////////////////////////////////////////////////////////////////////	
	
	$curl = curl_init($baseUrl . "/envelopes/" . $envelopeId );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
		"X-DocuSign-Authentication: $header" )                                                                       
	);

	$json_response = curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ( $status != 200 ) {
		echo "error calling webservice, status is:" . $status . "\nError text --> ";
		print_r($json_response); echo "\n";
		exit(-1);
	}

	//$response = json_decode($json_response, true);
	$response = $json_response;
	return $response;
}



//***************************************************
//Gets status from an array of envelope ids
//***************************************************

function GetMultiEnvelopeStatus($envIDArray) {
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 1 - login to DocuSign API
	/////////////////////////////////////////////////////////////////////////////////////////////////
	$docuCurlApiJson = ConnectDSCurlApi();
	$header = $docuCurlApiJson["header"];
	$accountId = $docuCurlApiJson["accountId"];
	$baseUrl = $docuCurlApiJson["baseUrl"];	

	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 2 - retrieve envelope status
	/////////////////////////////////////////////////////////////////////////////////////////////////
	
	$envIDList = implode(',', $envIDArray);
	
	$curl = curl_init($baseUrl . "/envelopes?envelope_ids=" . $envIDList );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
		'Accept: application/json',
		"X-DocuSign-Authentication: $header" )                                                                       
	);

	$json_response = curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ( $status != 200 ) {
		echo "error calling webservice, status is:" . $status . "\nerror text is --> ";
		//print_r($json_response); echo "\n";
		//exit(-1);
	}

	$response = json_decode($json_response, true);
	return $response;
}


//***************************************************
// Returns the Url for user to Sign DocuSign Envelope
//***************************************************

function GetEvelopeSignUrl($returnUrl, $envelopeId, $recipientName, $clientUserId, $email) {
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 1 - login to DocuSign API
	/////////////////////////////////////////////////////////////////////////////////////////////////
	$docuCurlApiJson = ConnectDSCurlApi();
	$header = $docuCurlApiJson["header"];
	$accountId = $docuCurlApiJson["accountId"];
	$baseUrl = $docuCurlApiJson["baseUrl"];	

	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 2 - get Envelope Signing url
	/////////////////////////////////////////////////////////////////////////////////////////////////
	$data = array("returnUrl" => $returnUrl,
		"authenticationMethod" => "None", "email" => $email, 
		"userName" => $recipientName, "clientUserId" => $clientUserId
	);                                                                    

	$data_string = json_encode($data);    
	$curl = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/views/recipient" );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_string),
		"X-DocuSign-Authentication: $header" )                                                                       
	);

	$json_response = curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ( $status != 201 ) {
		echo "error calling webservice, status is:" . $status . "\nerror text is --> ";
		print_r($json_response); echo "\n";
		exit(-1);
	}

	$response = json_decode($json_response, true);
	$url = $response["url"];
	
	return $url;
		
	
}

//************************************************
// Queries DocuSign for Envelope Statuses by RIA
// and updates doc_line db when status is complete
//************************************************

function UpdateEnvelopeStatus($ria_id) {

	//require_once('ChromePhp.php');
	//ChromePhp::log("Inside UpdateEnvelopeStatus");

	
	$con = db_connect();
	$riaEnvelopes = array();
	$newlySignedEnvelopes = array();
	$returnCode = "SUCCESS!";
	$updateDocLineQuery = "";
	$docEnvelope = array();
	$unSignedEnvelopes = array();
	
	$statusDocsQuery = "SELECT DISTINCT dl.docusign_env_id, dl.process_id, dl.sent_to_ria_user_id, dl.sent_to_ria_id, ";
	$statusDocsQuery .= "(SELECT GROUP_CONCAT(dl2.doc_header_id) FROM doc_line dl2 ";
	$statusDocsQuery .= "WHERE dl2.docusign_env_id = dl.docusign_env_id) AS doc_ids ";
	
	if (is_null($ria_id) || ($ria_id == "")) {
		//no ria_id provided - query all
		$statusDocsQuery .= "FROM doc_line dl where NOT(dl.docusign_env_id = '') and dl.signed_date is NULL";
	} else {
		//query by ria_id
		$statusDocsQuery .= "FROM doc_line dl where NOT(dl.docusign_env_id = '') and dl.sent_to_ria_id = " . $ria_id . " and dl.signed_date is NULL";
	}
	 
	
	try {

		$statusDocsResponse = mysqli_query($con,$statusDocsQuery);
		while ($statusDocsRow = mysqli_fetch_assoc($statusDocsResponse)) {
			array_push($riaEnvelopes, $statusDocsRow["docusign_env_id"]);
			$docEnvelope["docusign_env_id"] = $statusDocsRow["docusign_env_id"];
			$docEnvelope["process_id"] = $statusDocsRow["process_id"];
			$docEnvelope["sent_to_ria_id"] = $statusDocsRow["sent_to_ria_id"];
			$docEnvelope["sent_to_ria_user_id"] = $statusDocsRow["sent_to_ria_user_id"];
			$docEnvelope["doc_ids"] = $statusDocsRow["doc_ids"];
			array_push($unSignedEnvelopes, $docEnvelope);
		};

		if (count($riaEnvelopes) > 0 ) {
			$envelopeStatus = GetMultiEnvelopeStatus($riaEnvelopes);
			$envelopes = $envelopeStatus["envelopes"];
			foreach($envelopes as $envelope) {
				$signedEnv = array();
				$thisEnvStatus = $envelope['status'];
				$thisEnvId = $envelope['envelopeId'];
				$thisChgDateTimeStr = date('Y-m-d', strtotime($envelope['statusChangedDateTime']));
				//envelope status is complete?
				if ($thisEnvStatus == "completed") {
					$signedEnv = FindEnvelopeById($unSignedEnvelopes, $thisEnvId);
					array_push($newlySignedEnvelopes, $signedEnv);
					$updateDocLineQuery .= "update doc_line set signed_date = date_format('" . $thisChgDateTimeStr . "', '%Y-%m-%e') where docusign_env_id = '" . $thisEnvId . "'; ";
				}
			};
			
			
			//$returnCode = json_encode($envelopeStatus, true);
			//if there are newly signed docs then update doc_line
			if ($updateDocLineQuery != "") {
				if ($insertResult=mysqli_multi_query($con, $updateDocLineQuery)) {
					$returnCode = "SUCCESS!";
					do {
						// store first result set
						if ($updateDocLineResult = mysqli_store_result($con)) {
							while ($row = mysqli_fetch_row($updateDocLineResult)) {
								//nothing
							}
							mysqli_free_result($updateDocLineResult);
						}
					} while (mysqli_next_result($con));
					
					
				} else {
					$returnCode = "Error: " . mysqli_error($con);					
				}
			}
			
			
			//are there additional workflows for newly signed docs?
			//get workflow instructions and send out emails
			
			
			if (count($newlySignedEnvelopes) > 0) {
				
				$updateVeoQuery = "";
				
				foreach ($newlySignedEnvelopes as $signedEnv){
					//$docsJson = GetSignedDocumentsFromEnvelope($signedEnv["docusign_env_id"]);
					if ($signedEnv["process_id"] == "3") {
						//$docArray = array();
						$updateVeoQuery .= "update ria_veo_rep set doc_sent = 2 where ria_id = " . $signedEnv["sent_to_ria_id"] . " and send_doc_id = '" . $signedEnv["docusign_env_id"] . "'; ";
					}
				}
				
				//update TR ROI Workflow db
				if ($updateVeoQuery != "") {
					
					if ($updateVeoResult=mysqli_multi_query($con, $updateVeoQuery)) {
						$returnCode = "SUCCESS!";
						do {
							// store first result set - necessary when more than one mysqli_multi_query is used 
							if ($updateVeoRepResult = mysqli_store_result($con)) {
								while ($row = mysqli_fetch_row($updateVeoRepResult)) {
									//nothing
								}
								mysqli_free_result($updateVeoRepResult);
							}
						} while (mysqli_next_result($con));
						
						
					} else {
						$returnCode .= " Error: " . mysqli_error($con);							
					}					
				}
				
				
				//now send signed Doc Array for post processing
				$returnCode .= PostSignWorkFlow($newlySignedEnvelopes, $con);

				
			} else {
				$returnCode .= " No Recently Signed Docs";
			}

		}


	} catch(Exception $e) {
		$returnCode = $e;
	}
		
	return $returnCode;
}



//***************************************************
//Gets signed documents from envelope
//***************************************************

function GetSignedDocumentsFromEnvelope($envelopeId) {
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 1 - login to DocuSign API
	/////////////////////////////////////////////////////////////////////////////////////////////////
	$docuCurlApiJson = ConnectDSCurlApi();
	$header = $docuCurlApiJson["header"];
	$accountId = $docuCurlApiJson["accountId"];
	$baseUrl = $docuCurlApiJson["baseUrl"];	

	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 2 - retrieve envelope documents
	/////////////////////////////////////////////////////////////////////////////////////////////////	

	$statusString = "";
	//$curl = curl_init($baseUrl . "/envelopes/" . $envelopeId );
	//$curl = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/documents/" . $docId );
	//$curl = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/documents/combined");
	$curl = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/documents" );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		"X-DocuSign-Authentication: $header",
		"Accept: application/json",
		"Content-Type: application/json"
		)                                                                 
	);
	
	//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	$json_response = curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ( $status != 200 ) {
		//echo "error calling webservice, status is:" . $status;
		$statusString = "error calling webservice, status is:" . $status;
		//return $statusString;
		//exit(-1);
	}

	
	$response = json_decode($json_response, true);

	
	if(array_key_exists('errorCode', $response)) {
		$statusString .= " " . $response["errorCode"] . ": " . $response["message"];
	}

	
	curl_close($curl);
	

	$retrievedDocs = array();
	$retrievedDoc = array();
	
	foreach( $response["envelopeDocuments"] as $document ) {
		
		$retrievedDoc = array();
		$retrievedDoc['documentId'] = $document["documentId"];
		$retrievedDoc['name'] = $document["name"];
		$docUri = $document["uri"];
		
		$curl = curl_init($baseUrl . $docUri );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);  
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
			"X-DocuSign-Authentication: $header" )                                                                       
		);
		
		$data = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ( $status != 200 ) {
			$statusString .= " error calling webservice, status is:" . $status;
		}
		
		$retrievedDoc['pdfBytes'] = base64_encode($data);
		array_push($retrievedDocs,$retrievedDoc);
		

		//file_put_contents($envelopeId . "-" . $document["name"], $data);
		curl_close($curl);
	}
	
	//return json_encode($retrievedDocs);
	return $retrievedDocs;
	
}


//***************************************************
//Handles additional DocuSign workflows post-signing
//***************************************************

function PostSignWorkFlow($newlySignedEnvelopes, $con) {
	
	
	//require_once('ChromePhp.php');

	$returnCode = "";
	$numOfDocMailed = 0;
	$signedDocsArray = array();
	$separator = md5(uniqid(time()));
	////BEGIN this will come from db
	$postsign_workflow = array(
		array(
					"postsign_id" => "1",
					"process_id" => "3",
					"postsign_type" => "TDSharedRep",
					"doc_ids" => "1082,1083",
					"email_to" => "marc.reed@robustria.com",
					"email_cc" => "marc@marcreed.com",
					"email_from" => "service@robustwealth.com",
					"email_subj" => "Here is the Request for Shared Rep Code",
					"email_body" => "Here are two signed docs for Shared Rep Code"
		),
		array(
					"postsign_id" => "2",
					"process_id" => "3",
					"postsign_type" => "TDROI",
					"doc_ids" => "1084",
					"email_to" => "marc.reed@robustria.com",
					"email_cc" => "marc@marcreed.com",
					"email_from" => "service@robustwealth.com",
					"email_subj" => "Here is the ROI",
					"email_body" => "Here is one signed doc for ROI"
		)
    );
	////END this will come from db

	
	foreach ($newlySignedEnvelopes as $signedEnv){
		$docsJson = [];
		$thisProcessId = $signedEnv["process_id"];
		foreach ($postsign_workflow as $workflow) {
			if ($workflow["process_id"] == $thisProcessId) { 
				$msgTo = $workflow["email_to"];
				$msgCC = $workflow["email_cc"];
				$msgFrom = $workflow["email_from"];
				$msgSubject = $workflow["email_subj"];
				$msgText = $workflow["email_body"];
				//message header
				$msgHeader = ""; 
				$msgHeader .= "From: " . $msgFrom . "\r\n";
				if ($workflow["email_cc"] != "") {
					$msgHeader .= "Cc: " . $workflow["email_cc"] . "\r\n";
				}
				$msgHeader .= "MIME-Version: 1.0\r\nContent-Type:"." multipart/mixed; boundary=\"$separator\";\r\n"; 
				//message body
				$msgBody = "";
				$msgBody.= "--$separator\r\n"; 
				$msgBody.= "Content-Type: text/html; charset=\"iso-8859-1\"\r\n"; 
				$msgBody.= "Content-Transfer-Encoding: 7bit\r\n\n"; 
				$msgBody.= $msgText."\r\n\n";
				//create document attachments
				//first get all documents from envelope
				$docsJson = GetSignedDocumentsFromEnvelope($signedEnv["docusign_env_id"]);
				//append 'certificate' doc_id to workflow documents (doc id of certificate is 'certificate')
				$attachDocsList = $workflow["doc_ids"] . ",certificate";
				//ChromePhp::log("attachDocsList:" . $attachDocsList);
				//iterate thru workflow documents and attach
				$signedDocsArray = explode(",", $attachDocsList);
				//ChromePhp::log($signedDocsArray);
				foreach ($signedDocsArray as $workflowDocId) {
					foreach ($docsJson as $signedDoc) {
						$thisFileName = str_replace("'", "", $signedDoc["name"]);
						$thisFileName = str_replace(" ", "_", $thisFileName);
						$thisFileName .= ".pdf";
						if ($signedDoc["documentId"] == $workflowDocId) {
							//ChromePhp::log("MATCH docusign docId:" . $signedDoc["documentId"] . ", workflowDocId:" . $workflowDocId);
							$msgBody.= "--$separator\r\n"; 
							$msgBody.= "Content-Type: application/pdf; name=\"" . $thisFileName . "\"\r\n"; 
							$msgBody.= "Content-Transfer-Encoding: base64\r\n"; 
							$msgBody.= "Content-Disposition: attachment; filename=\"" . $thisFileName . "\"\r\n\n"; 
							$msgBody.= chunk_split($signedDoc["pdfBytes"])."\r\n";
						}
					}
				}
				$msgBody.= "--$separator--\r\n"; 
				//mail it
				$success = mail($msgTo, $msgSubject, $msgBody, $msgHeader);
				$numOfDocMailed++;
			}
		}
	}
	if ($numOfDocMailed > 0) {
		$returnCode .= " " . $numOfDocMailed . " emails sent.";
	}	
	$error = error_get_last();
	if (count($error) > 0) {
		$returnCode .= " Message:" . $error["message"] . ", file:" . $error["file"] . ", line:" . $error["line"];
	}

	return $returnCode;
}


//Populates DocuSign Tags from xml tabNode
function SetManualDocuTab($tabArr, $tabNode) {
	$tabArr["xPosition"] = (string)$tabNode->XPosition;
	$tabArr["yPosition"] = (string)$tabNode->YPosition;
	$tabArr["documentId"] = (string)$tabNode->DocumentID;
	$tabArr["pageNumber"] = (string)$tabNode->PageNumber;
	//$tabArr["recipientId"] = (string)$tabNode->RecipientID;
	return $tabArr;
}


//Populates DocuSign Auto-Tags from xml tabNode
function SetAutoDocuTab($tabArr, $tabNode) {
	$tabArr["anchorXOffset"] = (string)$tabNode->XPosition;
	$tabArr["anchorYOffset"] = (string)$tabNode->YPosition;
	$tabArr["anchorString"] = (string)$tabNode->AutofillString;
	//$tabArr["recipientId"] = (string)$tabNode->RecipientID;
	return $tabArr;
}

//Returns array item that matches envelope id
function FindEnvelopeById($allEnvArray, $envId) {
	$returnEnv = array();
	foreach( $allEnvArray as $docEnvelope ) {
		if ($docEnvelope["docusign_env_id"] == $envId) {
			$returnEnv = $docEnvelope;
			break;
		}
	}
	return $returnEnv;
}







?>
