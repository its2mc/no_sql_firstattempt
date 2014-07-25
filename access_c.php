<?php

//Declaration of Global variables
$choice= $_GET['code']; 
$servername="localhost";
$username="root";
$password="mysql";
$db="test";
$fieldlist = array('int1_txt','int2_txt','int3_txt','int4_txt','date1_txt','date2_txt','date3_txt','date4_txt','text1_txt','text2_txt','text3_txt','text4_txt');


class unidbapi { 
	public $mergefieldnames;
	
//**************************************************
//*          Connect Db Function                   *
//*                                                * 
//*                                                *
//**************************************************

	function connectdb(){
		$conn = mysql_connect($servername,$username,$password) or
		die ("Error Connecting to MySql");
		mysql_select_db($db) or die ("Error Connecting to myDatabase");
	}

//**************************************************
//*             Fix String Function                *
//*                                                * 
//*                                                *
//**************************************************

	function fixstring($keyname){
		$keyname = htmlspecialchars(stripslashes($keyname));
		$keyname = mysql_escape_string($keyname);
		return $keyname;
	}
	
//**************************************************
//*             Encode Function                    *
//*             Base64 Encode                      * 
//*                                                *
//**************************************************
	function encode($string){
		return base64_encode($string);
	}
	
//**************************************************
//*             Decode Function                    *
//*             Base64 Decode                      * 
//*                                                *
//**************************************************
	function decode($string){
		return base64_decode($string);
	}
	
//**************************************************
//*             Timer Function                     *
//*                Float                           * 
//*                                                *
//**************************************************
	function timestamp(){
    	list($usec, $sec) = explode(" ", microtime());
   		return ((float)$usec + (float)$sec);
	}

//**************************************************
//*        Array Serialization Function            *
//*   Changes an array into serialized string      * 
//*                                                *
//**************************************************
	function serialarray($array){
    	$serialstring=mysql_escape_string(serialize($array));
   		return $serialstring;
	}

//**************************************************
//*          Array Unserialize Function            *
//*  Changes an serialized string into an array    * 
//*                                                *
//**************************************************
	function unserialstring($string){
    	$unserialarray= unserialize($string);
   		return $unserialarray;
	}

//**************************************************
//*            Error Logging Function              *
//*  Logs any errors in script to a Log.txt on     * 
//*                  The server                    *
//**************************************************
	function logerror($message){
 //Checks if File exists, if not creates a file
    	
 //Creates error entry with the date and message
 
 //If successful dies with success message else dies with failed message.
	}

//**************************************************
//*           Fish Data Function                   *
//*                                                * 
//*                                                *
//**************************************************

	function fishdata($keyname){
		if($appid!=''){
			connectdb();
			$query = "SELECT * FROM identification WHERE AppID='$appid'||KeyName='$keyname'";
			$select = mysql_query($query) or die ("Cannot Select from Identification");
			$row=mysql_fetch_array($select);
			$result=unserialstring($row['Information1']);
			return $result;
			mysql_close($conn);
		}
		else die("Error with APP ID");
	}

//**************************************************
//*           Generate fields Function             *
//*       This generaters fields from an           * 
//*        input form to be inserted               *
//**************************************************

	function genvalues($keyname){
		$fieldarray = fishdata($keyname);
		$c=0;
		while($fieldarray[$c]!=end($fieldarray)) 
		{
			if($fieldarray[$c]!='NULL'){
				$genvalues = $genvalues + " '$" + $fieldarray[$c]+"'";
			}
			else{
				$temp = rand('5','233');
				$genvalues = $genvalues + '' +$temp;
			}
			$genvalues = $genvalues.',';
		}
		$genvalues = $genvalues +"'"+end($fieldarray)+"'";
		return $genvalues;
	}

//**************************************************
//*             Insert Function                    *
//*                                                * 
//*                                                *
//**************************************************

	function insert($fields,$values,$conditions){
		connectdb();
		$query="INSERT INTO main (ID,AppID,KeyID,INT1,INT2,INT3,INT4,DATE1,DATE2,DATE3,DATE4,TEXT1,TEXT2,TEXT3,TEXT4) VALUES (NULL,'$appid','$keyid',".$values.") WHERE".$conditions;
		$select=mysql_query($query) or die("Error in inserting values into fields");
		mysql_close($conn);
	}


//**************************************************
//*             Delete Function                    *
//*                                                * 
//*                                                *
//**************************************************

	function delete($condition){
		connectdb();
		$delete ="DELETE FROM main WHERE ".$condition;
		$query = mysql_query($delete) or die("Error in deleting values");
		mysql_close($conn);
	}

//**************************************************
//*             Update Function                    *
//*                                                * 
//*                                                *
//**************************************************

	function update($values,$conditions){
		connectdb();
		$query="UPDATE main SET ".$values." WHERE ".$conditions;
		$select=mysql_query($query) or die("Error in Updating Values");
		mysql_close($conn);
	}
	
//**************************************************
//*             Select Function                    *
//*                                                * 
//*                                                *
//**************************************************

	function select($fields,$keyname,$condition){
		connectdb();
		$query = "SELECT ".$fields." FROM main WHERE ".$condition;
		$select = mysql_query($query) or die ("Error in Select Query");
		$row=mysql_fetch_array($select);
		mysql_close($conn);
	}
	
//**************************************************
//*             Merge Append Function              *
//*   Automatically generates the fields from 2    * 
//*                  tables                        *
//**************************************************

//Y this is here
	function mergeappend($keyname1,$keyname2) {
		$mergefieldnames[0] = fishdata($keyname1);
		$mergefieldnames[1] = fishdata($keyname2);
		return $mergefieldnames;
	}

//**************************************************
//*              Merge Function                    *
//*                                                * 
//*                                                *
//**************************************************

/*	function selectmerge($fields,$keyname1,$keyname2,$condition1,$condition2,$parameters){
		connectdb();
		$query = "SELECT ".$fields." FROM ".$keyname1." t1 LEFT JOIN ".$table2." t2 ON ".$condition2." WHERE ".$condition1." ".$parameters;
		$select = mysql_query($query) or die ("Error in Select Merge Query");
		$row=mysql_fetch_array($select);
		mysql_close($conn);
	}*/
	
//**************************************************
//*           Create Table Function                *
//* This function creates a new table by inserting * 
//*             the tables information             *
//**************************************************

	function createtable($appid,$app_passkey,$keyid,$keyname,$serial_string){
		connectdb();
		$chk_query="SELECT * FROM identification WHERE AppID='$appid'||KeyName='$keyname'";
		$chk_select=mysql_query($chk_query) or die ("Cannot check query in create table function");
		if(mysql_num_rows($chk_select)!=0) {echo "The table already exists"; break;}
		
		$query="INSERT INTO identification (ID,AppID,AppPasskey,KeyID,KeyName,Information1,Information2) VALUES (NULL,'$appid','$app_passkey','$keyid','$keyname','$serial_string',NULL)";
		$select=mysql_query($query) or die("Error in inserting values into fields");
		mysql_close($conn);
	}
		
//**************************************************
//*           Delete Table Function                *
//*                                                * 
//*                                                *
//**************************************************

	function deletetable($app_passkey,$keyname){
		connectdb();
		$chk_query="SELECT ID FROM identification WHERE AppPasskey='$app_passkey'||KeyName='$keyname'";
		$chk_select=mysql_query($chk_query) or die ("Cannot check query in create table function");
		if(mysql_num_rows($chk_select)==0) {echo "The table does not exist!"; break;}
		$row = mysql_fetch_array($chk_select);
		$id=$row[0];
		
		$query="DELETE FROM information WHERE ID='$id'";
		$select=mysql_query($query) or die("Error in function delete table, could not execute query");
		mysql_close($conn);
	}
	
//**************************************************
//*           Install API Function                 *
//*                                                * 
//*                                                *
//**************************************************
//Creates the Databases, Creates the Main Tables Needed and checks if they are functioning.

	function installapi($servername,$username,$password){
		$conn = mysql_connect($servername,$username,$password) or
		die ("Error Connecting to MySql");	
//Creates the Database	
		$createdb = "CREATE DATABASE 'unidbapi'";
		$query = mysql_query($createdb) or die("Error in creating a database");
//Creates the main table
		$createtable1 = "CREATE TABLE  `unidbapi`.`main` (`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`AppID` INT NOT NULL ,`KeyID` INT NOT NULL ,`INT1` INT NOT NULL ,`INT2` INT NOT NULL ,`INT3` INT NOT NULL ,`INT4` INT NOT NULL ,`DATE1` DATE NOT NULL ,`DATE2` DATE NOT NULL ,`DATE3` DATE NOT NULL ,`DATE4` DATE NOT NULL ,`TEXT1` TEXT NOT NULL ,`TEXT2` TEXT NOT NULL ,`TEXT3` TEXT NOT NULL ,`TEXT4` TEXT NOT NULL ) ENGINE = INNODB";
		$query = mysql_query($createtable1) or die("Cannot create the main table");
//Creates the information Table
		$createtable2 = "CREATE TABLE  'unidbapi'.'information'('ID' INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,'AppID' INT NOT NULL ,'AppPasskey' TEXT NOT NULL ,'KeyID' INT NOT NULL ,'KeyName' TEXT NOT NULL ,'Information1' TEXT NOT NULL ,'Information2' TEXT NOT NULL ) ENGINE = INNODB";
		$query = mysql_query($createtable1) or die("Cannot create a tables");

		$checkdb = "DROP TABLE ".$tablename;
		if($query = mysql_query($deltable)) printf("API Installation Successful");
		else die ("API Installation error.");
		mysql_close($conn);
	}

//**************************************************
//*              App Check Function                *
//*  Searches the information database for an      * 
//*         app match and gives the app ID         *
//**************************************************
//parameter includes WHERE.... and ORDER BY. If none put ''
	function nxtid($item,$passkey,$field,$parameter){
		connectdb();
		$search = "SELECT ".$field." FROM identification ".$parameter;
		$select = mysql_query($search) or die("Cannot process search Query");
		if(mysql_num_rows($select)!=0){
			while($row=mysql_fetch_array($select)) {
				$nextid = $row[$counter++];
			}
			$nextid++;
		}
		else if(mysql_num_rows($select)==0){
			while($row=mysql_fetch_array($select)) {
				$nextid = $row[$counter++];
			}
		}
		else die("Error in Processing request function nxt id, mysql_query");
		return $nextid;
	}
	
	
}


?>