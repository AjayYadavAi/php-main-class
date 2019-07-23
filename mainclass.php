<?php
session_start();
error_reporting(0);
class mainclass{

	public function __construct(){
		$this->con = mysqli_connect('localhost','username','password','databasename') or die("Error ". mysqli_error($con));
		if(!$this->con){
			throw new exception('Could not connect to database server');
		}
		
	}


	#Fetcha data on the bases of the table name
	function fetchall($table){
		$result = $this->con->query("SELECT * FROM $table ORDER BY id DESC");
		$data = array();
		while($row = $result->fetch_array()){
			$data[] = $row;
		}
		return $data;
	}

	#Login Validation to the server
	function login($table,$col1,$col2,$val1,$val2){
		$result = $this->con->query("SELECT * FROM $table where $col1='$val1' and $col2='$val2'");
		$data = array();
		$data = $result->fetch_array();
		return $data;
	}
	

	function fetchbyid($table,$col,$id){
		$result = $this->con->query("SELECT * FROM $table where $col='$id'");
		$row = $result->fetch_array();
		return $row;
	}

	function fetchallbyid($table,$col,$id){
		$result = $this->con->query("SELECT * FROM $table where $col='$id'");
		$data = array();
		while($row = $result->fetch_array()){
			$data[] = $row;
		}
		return $data;
	}	

	function updatedata($table,$data,$col,$id){
		$d = '';
		foreach($data as $key=>$value){
			$d .= $key.'='."'$value',";
		}
		$d = substr_replace($d, "", -1);
		$q = "UPDATE $table set $d where $col = '$id'";
		$result = $this->con->query($q);
		return $q;
	}

	#delete data

	function deletedata($table,$col,$id){
		$q = "DELETE FROM $table where $col='$id' limit 1";
		if($this->con->query($q)){
			return 1;
		}else{
			return 0;
		}
	}

	#add Data 

	function adddata($table,$data){
		$keys = $this->array_keys_value($data);
		$values = $this->array_values_value($data);
		$q = "INSERT INTO $table($keys) VALUES($values)";
		if($this->con->query($q)){
			return $this->con->insert_id;
		}else{
			return 0;
		}
	}


	#all Rows

	function numrows($table){
		$q = "SELECT count(*) as total FROM $table";
		$result = $this->con->query($q);
		$result = $result->fetch_assoc();
		return $result;
	}

	#numrows with where

	function numrowswhere($table,$col,$id){
		$q = "SELECT count(*) as total FROM $table where $col = '$id'";
		$result = $this->con->query($q);
		$result = $result->fetch_assoc();
		return $result;
	}

	function numrowdate($table,$col,$id){
		$q = "SELECT count(*) as total FROM $table where $col like '$id%'";
		$result = $this->con->query($q);
		$result = $result->fetch_assoc();
		return $result;
	}

	#myquery

	function myquery($sql){
		$result = $this->con->query($sql);
		$data = array();
		while($row = $result->fetch_array()){
			$data[] = $row;
		}
		return $data;
	}

	# select one column from a table

	function fetchSelectedCol($table,$data){
		$cols = $this->arrayToStr($data);
		$q = "SELECT $cols from $table";
		$result = $this->con->query($q);
		$data = array();
		while($row= $result->fetch_array()){
			$data[] = $row;
		}
		return $data;
	}

	// Profile meter

	function profile_meter(){
		$q = "SELECT user.id as user_id, user.summary as summary, user.skill as skill, user.desire_location as desire_location, user.desire_industry as desire_industry, user.desire_functional_area as desire_functional_area, user.desire_role as desire_role, user.user_img as user_img,user.phone_varified as phone_varified, user.email_varified as email_varified, user_language.name as ul,user_experience.title as uex, user_cv.usercv as usercv,user_education.level as user_education from user INNER JOIN user_language on user.id = user_language.user_id INNER JOIN user_experience ON user.id = user_experience.user_id INNER JOIN user_cv on user.id=user_cv.user_id INNER JOIN user_education on user.id = user_education.user_id";
		$result = $this->con->query($q);
		$count = [];
		$ids = [];
		while ($row = $result->fetch_array()) {
			
			if(in_array($row['user_id'], $ids)){
			}else{				
				$total = 0;
				if(strlen($row['summary'])>0){$total +=8;};
				if(strlen($row['skill'])>0){$total +=10;};
				if(strlen($row['desire_location'])>0){$total +=2;};
				if(strlen($row['desire_industry'])>0){$total +=2;};
				if(strlen($row['desire_functional_area'])>0){$total +=10;};
				if(strlen($row['desire_role'])>0){$total +=8;};
				if(strlen($row['user_img'])>0){$total +=5;};
				if(strlen($row['ul'])>0){$total +=2;};
				if(strlen($row['uex'])>0){$total +=18;};
				if(strlen($row['usercv'])>0){$total +=10;};
				if(strlen($row['user_education'])>0){$total +=10;};
				if(strlen($row['phone_varified'])>0){$total +=5;};
				if(strlen($row['email_varified'])>0){$total +=5;};
				$total += 5;
				if($total==100){
					$count[] = $total;					
				}
				$ids[] = $row['user_id'];
			}

		}
		
		return count($count);
	}


//8+10+2+2+10+8+5+2+18+10+10+5+5+5


	function newfetch($table){
		$result = $this->con->query("SELECT * FROM $table ORDER BY id DESC");
		$data = array();
		while($row = $result->fetch_row()){
			$data[] = $row;
		}
		return $data;
	}







function array_keys_value($array)
{
	$a = '';
    foreach ($array as $key => $value) {
        $a .=$key.',';
    }
	$a = substr_replace($a, "", -1);
    return $a;
}
function array_values_value($array)
{
	$a = '';
    foreach ($array as $key => $value) {
        $a .="'$value',";
    }
	$a = substr_replace($a, "", -1);
    return $a;
}

// Columns for the function of the website

function arrayToStr($arr){
	$b = '';
	for($i=0;$i<count($arr);$i++){
		$b .=$arr[$i].',';
	}
	$b = substr_replace($b, "", -1);
	return $b;
}



}
