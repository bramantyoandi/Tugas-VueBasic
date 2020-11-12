<?php

$host = "localhost"; 
$user = "root";        
$password = "";         
$dbname = "Tugas-VueBasic";  

$con = mysqli_connect($host, $user, $password,$dbname);

if (!$con) die("Connection failed: " . mysqli_connect_error());



$act = !empty($_GET['act']) ? $_GET['act'] : '';

$prioritas = [
	1	=> ['label' => 'Penting', 'warna' => 'label label-danger'],
	2	=> ['label' => 'Biasa', 'warna' => 'label label-primary'],
	3	=> ['label' => 'Tidak Penting', 'warna' => 'label label-success'],
];

switch ($act) {

	case 'list':
		
		$data = mysqli_query($con,"select * from note order by id desc");

		$response = array();
		while($row = mysqli_fetch_assoc($data)){
			$rows = [];
			$rows['id'] = $row['id'];
			$rows['idprioritas'] = $row['prioritas'];
			$rows['todo'] = $row['todo'];
			$rows['prioritas'] = '<label class="'.$prioritas[$row['prioritas']]['warna'].'">'.$prioritas[$row['prioritas']]['label'].'</label>';
			$rows['catatan'] = $row['catatan'];
		    $response[] = $rows;
		}

		die(json_encode($response));

		break;

	case 'simpan':
		
			$request = json_decode(file_get_contents("php://input"));

			$data = new \stdClass;

			$idnote = mysqli_fetch_assoc(mysqli_query($con,"select max(id) id from note"))['id'];
			if($idnote) {
				$idnote = $idnote+1;
			}
			else {
				$idnote = 1;
			}

			$data->id = $idnote;
			$data->nama = $request->nama;
			$data->prioritas = $request->prioritas;
			$data->catatan = $request->catatan;

			$sql = "INSERT INTO note(id,todo,prioritas,catatan) VALUES('".$data->id."','".$data->nama."','".$data->prioritas."','".$data->catatan."')";

			$query = mysqli_query($con, $sql);
			if ($query) {
				die('1');
			} else {
				die('0');
			}
			exit;

		break;

	case 'update':
			
			$request = json_decode(file_get_contents("php://input"));

			$data = new \stdClass;

			$data->id = $request->id;
			$data->nama = $request->nama;
			$data->prioritas = $request->prioritas;
			$data->catatan = $request->catatan;

			$sql = "UPDATE note SET todo = '$data->nama', prioritas = '$data->prioritas', catatan = '$data->catatan' WHERE id = '$data->id'";

			$query = mysqli_query($con, $sql);
			if ($query) {
				die('1');
			} else {
				die('0');
			}
			exit;

		break;

	case 'hapus':

			$request = json_decode(file_get_contents("php://input"));

			$data = new \stdClass;

			$data->id = $request->id;

			$sql = "DELETE FROM note WHERE id = '$data->id'";

			mysqli_query($con, $sql);

			echo 'sukses';
			exit;
		
		break;
	
	default:
		// code...
		break;
}
