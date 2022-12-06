<?php
	require_once('dbconnect.php');

	$requestMethod = $_SERVER["REQUEST_METHOD"];
	
	//ตรวจสอบหากใช้ Method GET
	if($requestMethod == 'GET'){
		//ตรวจสอบการส่งค่า id
		if(isset($_GET['m_id']) && !empty($_GET['m_id'])){
			
			$m_id = $_GET['m_id'];
			
			//คำสั่ง SQL กรณี มีการส่งค่า id มาให้แสดงเฉพาะข้อมูลของ id นั้น
			$sql = "SELECT * FROM member WHERE m_id = $m_id";
			
		}else if(!isset($_GET['m_id']) && empty($_GET['m_id'])){
            //คำสั่ง SQL แสดงข้อมูลทั้งหมด
            $sql = "SELECT * FROM member";
        }else{
            http_response_code(404);
        }
		
		$result = mysqli_query($link, $sql);
		
		//สร้างตัวแปร array สำหรับเก็บข้อมูลที่ได้
		$arr = array();
		
		while ($row = mysqli_fetch_assoc($result)) {
			
			$row["m_id"] = intval($row["m_id"]);
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
			
			$m_email = $result['m_email'];
			$m_password = $result['m_password'];
			$m_name = $result['m_name'];
			
			//คำสั่ง SQL สำหรับเพิ่มข้อมูลใน Database
			$sql = "INSERT INTO member (m_id,m_email,m_password,m_name) VALUES (NULL,'$m_email','$m_password','$m_name')";
			
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
		if(isset($_GET['m_id']) && !empty($_GET['m_id'])){
			
			$m_id = $_GET['m_id'];
			
			$m_email = $result['m_email'];
			$m_password = $result['m_password'];
			$m_name = $result['m_name'];
			
			//คำสั่ง SQL สำหรับแก้ไขข้อมูลใน Database โดยจะแก้ไขเฉพาะข้อมูลตามค่า id ที่ส่งมา
			$sql = "UPDATE member SET m_email = '$m_email' , m_password = '$m_password' , m_name = '$m_name' WHERE m_id = $m_id";

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
		if(isset($_GET['m_id']) && !empty($_GET['m_id'])){
			
			$m_id = $_GET['m_id'];
			
			//คำสั่ง SQL สำหรับลบข้อมูลใน Database ตามค่า id ที่ส่งมา
			$sql = "DELETE FROM member WHERE m_id = $m_id";

			$result = mysqli_query($link, $sql);
			
			if ($result) {
				http_response_code(200);
			} else {
				http_response_code(404);
			}
		
		}
			
	}