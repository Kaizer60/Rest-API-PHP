<?php
	require_once('dbconnect.php');

	$requestMethod = $_SERVER["REQUEST_METHOD"];
	
	//ตรวจสอบหากใช้ Method GET
	if($requestMethod == 'GET'){
		//ตรวจสอบการส่งค่า id
        if(isset($_GET['m_id']) && !empty($_GET['m_id'])){
			
			$m_id = $_GET['m_id'];
			
			//คำสั่ง SQL กรณี มีการส่งค่า id มาให้แสดงเฉพาะข้อมูลของ id นั้น
			$sql = "SELECT * FROM enroll WHERE m_id = $m_id";
			
		}else if(isset($_GET['c_id']) && !empty($_GET['c_id'])){
			
			$c_id = $_GET['c_id'];
			
			//คำสั่ง SQL กรณี มีการส่งค่า id มาให้แสดงเฉพาะข้อมูลของ id นั้น
			$sql = "SELECT * FROM enroll WHERE c_id = $c_id";
			
		}else if(isset($_GET['cer_id']) && !empty($_GET['cer_id'])){
			
			$cer_id = $_GET['cer_id'];
			
			//คำสั่ง SQL กรณี มีการส่งค่า id มาให้แสดงเฉพาะข้อมูลของ id นั้น
			$sql = "SELECT * FROM enroll WHERE cer_id = $cer_id";
			
		}else if(!isset($_GET['cer_id']) && empty($_GET['cer_id'])){
			
			//คำสั่ง SQL กรณี มีการส่งค่า id มาให้แสดงเฉพาะข้อมูลของ id นั้น
			$sql = "SELECT * FROM enroll";
			
		}else{
            http_response_code(404);
        }

		// if(isset($_GET['cer_id']) && !empty($_GET['cer_id'])){
			
		// 	$cer_id = $_GET['cer_id'];
			
		// 	//คำสั่ง SQL กรณี มีการส่งค่า id มาให้แสดงเฉพาะข้อมูลของ id นั้น
		// 	$sql = "SELECT * FROM enroll WHERE cer_id = $cer_id";
			
		// }else if(!isset($_GET['cer_id']) && empty($_GET['cer_id'])){
        //     //คำสั่ง SQL แสดงข้อมูลทั้งหมด
        //     $sql = "SELECT * FROM enroll";

        // }else if(isset($_GET['m_id']) && !empty($_GET['m_id'])){
        //     $m_id = $_GET['m_id'];
			
		// 	//คำสั่ง SQL กรณี มีการส่งค่า id มาให้แสดงเฉพาะข้อมูลของ id นั้น
		// 	$sql = "SELECT * FROM enroll WHERE m_id = $m_id";
            
        // }else{
        //     http_response_code(404);
        // }
		
		$result = mysqli_query($link, $sql);
		
		//สร้างตัวแปร array สำหรับเก็บข้อมูลที่ได้
		$arr = array();
		
		while ($row = mysqli_fetch_assoc($result)) {
			
			$row["cer_id"] = intval($row["cer_id"]);
            $row["m_id"] = intval($row["m_id"]);
            $row["c_id"] = intval($row["c_id"]);
            $row["cer_start"] = strtotime($row["cer_start"]);
            $row["cer_start"] = date("d-m-Y", $row["cer_start"]);
            $row["cer_expire"] = strtotime($row["cer_expire"]);
            $row["cer_expire"] = date("d-m-Y", $row["cer_expire"]);
                               
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
			
			$m_id = $result['m_id'];
			$c_id = $result['c_id'];
			$cer_start = $result['cer_start'];
            $cer_expire = $result['cer_expire'];
			
			//คำสั่ง SQL สำหรับเพิ่มข้อมูลใน Database
			$sql = "INSERT INTO enroll (cer_id,m_id,c_id,cer_start,cer_expire) VALUES (NULL,'$m_id','$c_id','$cer_start','$cer_expire')";
			
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
		if(isset($_GET['cer_id']) && !empty($_GET['cer_id'])){
			
			$cer_id = $_GET['cer_id'];
			
			$m_id = $result['m_id'];
			$c_id = $result['c_id'];
			$cer_start = $result['cer_start'];
            $cer_expire = $result['cer_expire'];
			
			//คำสั่ง SQL สำหรับแก้ไขข้อมูลใน Database โดยจะแก้ไขเฉพาะข้อมูลตามค่า id ที่ส่งมา
			$sql = "UPDATE enroll SET m_id = '$m_id' , c_id = '$c_id' , cer_start = '$cer_start' , cer_expire = '$cer_expire' WHERE cer_id = $cer_id";

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
		if(isset($_GET['cer_id']) && !empty($_GET['cer_id'])){
			
			$cer_id = $_GET['cer_id'];
			
			//คำสั่ง SQL สำหรับลบข้อมูลใน Database ตามค่า id ที่ส่งมา
			$sql = "DELETE FROM enroll WHERE cer_id = $cer_id";

			$result = mysqli_query($link, $sql);
			
			if ($result) {
				http_response_code(200);
			} else {
				http_response_code(404);
			}
		
		}
			
	}