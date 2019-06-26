<?php
session_start();
class mainclass{
	private $fcon;
	private $servername = "localhost";
	private $username = "root";
	private $password = "";
	private $databasename = "veghub";

	public function __construct(){
		$this->con = mysqli_connect('localhost','root','','madhyam') or die("Error ". mysqli_error($con));
		if(!$this->con){
			throw new exception('Could not connect to database server');
		}
	}

	#Fetcha data on the bases of the table name
	function fetchall($table){
		$result = $this->con->query("SELECT * FROM $table");
		$data = array();
		while($row = $result->fetch_array()){
			$data[] = $row;
		}
		return $data;
	}

	#Login Validation to the server
	function login($table,$col1,$col2,$val1,$val2){
		$result = $this->con->query("SELECT * FROM $table where $col1='$val1' and $col2='$val2'");
		$_SESSION['admin'] = $val2;
		$data = array();
		$data = $result->fetch_array();
		return $data;
	}
	function allproduct(){
		$q = "SELECT p.*,c.* from product p INNER JOIN category c on p.cat_id = c.category_id";
		$result = $this->con->query($q);
		$data = array();
		while($row = $result->fetch_array()){
			$data[] = $row;
		}
		return $data;
	}

	function fetchbyid($table,$col,$id){
		$result = $this->con->query("SELECT * FROM $table where $col='$id'");
		$row = $result->fetch_array();
		return $row;
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
		$q = "DELETE * FROM $table where $col='$id' limit 1";
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
			return 1;
		}else{
			return 0;
		}
	}

	# All Sales

	function allsales(){
		$q = "SELECT s.*,u.*,p.* from sales s INNER JOIN user u on s.user_id = u.user_id INNER JOIN product p on s.product_id = p.product_id";
		$result = $this->con->query($q);
		$data = array();
		while($row = $result->fetch_array()){
			$data[] = $row;
		}
		return $data;
	}

	# All Feedback

	function allfeedback(){
		$q = "SELECT f.*,p.*,u.* from feedback f INNER JOIN product p on p.product_id = f.product_id INNER JOIN user u on u.user_id = f.user_id";
		$result = $this->con->query($q);
		$data = array();
		while($row = $result->fetch_array()){
			$data[] = $row;
		}
		return $data;
	}

	#all Rows

	function numrows($table){
		$q = "SELECT * FROM $table";
		$result = $this->con->query($q);
		return $result;
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



}
