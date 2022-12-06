<?php
	require_once('dbconnect.php');

	$requestMethod = $_SERVER["REQUEST_METHOD"];
	
	//ตรวจสอบหากใช้ Method GET
	if($requestMethod == 'GET'){
		//ตรวจสอบการส่งค่า id
		if(isset($_GET['c_id']) && !empty($_GET['c_id'])){
			
			$c_id = $_GET['c_id'];
			
			//คำสั่ง SQL กรณี มีการส่งค่า id มาให้แสดงเฉพาะข้อมูลของ id นั้น
			$sql = "SELECT * FROM course WHERE c_id = $c_id";
			
		}else if(!isset($_GET['c_id']) && empty($_GET['c_id'])){
            //คำสั่ง SQL แสดงข้อมูลทั้งหมด
            $sql = "SELECT * FROM course";
        }else{
            http_response_code(404);
        }
		
		$result = mysqli_query($link, $sql);
		
		//สร้างตัวแปร array สำหรับเก็บข้อมูลที่ได้
		$arr = array();
		
		while ($row = mysqli_fetch_assoc($result)) {
			
			$row["c_id"] = intval($row["c_id"]);
            $row["c_price"] = number_format($row["c_price"],2);
            $row["c_price"] = floatval($row["c_price"]);                     
			$arr[] = $row;
		}
		
		echo json_encode($arr);
	}
	
	//อ่านข้อมูลที่ส่งมาแล้วเก็บไว้ที่ตัวแปร data
	$data = file_get_contents("php://input");

	//แปลงข้อมูลที่อ่านได้ เป็น array แล้วเก็บไว้ที่ตัวแปร result
	$result = json_decode($data,true);

	//ตรวจสอบการเรียกใช้งานว่าเป็น Method POST หรือไม่
	if($requestMethod == 'POST'){
		
		if(!empty($result)){
			
			$c_name = $result['c_name'];
			$c_description = $result['c_description'];
			$c_price = $result['c_price'];
			
			//คำสั่ง SQL สำหรับเพิ่มข้อมูลใน Database
			$sql = "INSERT INTO course (c_id,c_name,c_description,c_price) VALUES (NULL,'$c_name','$c_description','$c_price')";
			
			$result = mysqli_query($link, $sql);
			
			if ($result) {
				http_response_code(200);
			} else {
				http_response_code(404);
			}
		}
			
	}
	
	//ตรวจสอบการเรียกใช้งานว่าเป็น Method PUT หรือไม่
	if($requestMethod == 'PUT'){
		
		//ตรวจสอบว่ามีการส่งค่า id มาหรือไม่
		if(isset($_GET['c_id']) && !empty($_GET['c_id'])){
			
			$c_id = $_GET['c_id'];
			
			$c_name = $result['c_name'];
			$c_description = $result['c_description'];
			$c_price = $result['c_price'];
			
			//คำสั่ง SQL สำหรับแก้ไขข้อมูลใน Database โดยจะแก้ไขเฉพาะข้อมูลตามค่า id ที่ส่งมา
			$sql = "UPDATE course SET c_name = '$c_name' , c_description = '$c_description' , c_price = '$c_price' WHERE c_id = $c_id";

			$result = mysqli_query($link, $sql);
			
			if ($result) {
				http_response_code(200);
			} else {
				http_response_code(404);
			}
		
		}
			
	}
	
	//ตรวจสอบการเรียกใช้งานว่าเป็น Method DELETE หรือไม่
	if($requestMethod == 'DELETE'){
		
		//ตรวจสอบว่ามีการส่งค่า id มาหรือไม่
		if(isset($_GET['c_id']) && !empty($_GET['c_id'])){
			
			$c_id = $_GET['c_id'];
			
			//คำสั่ง SQL สำหรับลบข้อมูลใน Database ตามค่า id ที่ส่งมา
			$sql = "DELETE FROM course WHERE c_id = $c_id";

			$result = mysqli_query($link, $sql);
			
			if ($result) {
				http_response_code(200);
			} else {
				http_response_code(404);
			}
		
		}
			
	}