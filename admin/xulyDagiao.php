<?php
	if(isset($_POST['idoder']))
	{
		include('../kiemtrasession.php');
		$idoder=$_POST['idoder'];
		include('../connect.php');
		$class=new Database();
		$sq1=$class->connect();
		$data="status = 3";
		$table='oder';
		$class->updateOrder($table,$data,$idoder);
		//Lấy id cuoi cùng trong table
		$sqlid="Select id from cart";
		$result=mysqli_query($sq1,$sqlid);
		$num=mysqli_num_rows($result);
		//Lấy dữ liệu từ đơn hàng bỏ sang hóa đơn
		$sqloder="Select * from oder where id=$idoder";
		$dataoder=$class->query($sqloder);
		foreach($dataoder as $key=>$value){
			$data1=array('id' => $num+1,
						'user_username' => $value['user_username'],
						//'employee' => $_SESSION['useradmin'],
						'total_price' => $value['total_price'],
						'created' => date('y-m-d'),
						'address' => $value['address'],
						'status' => 1,
						);
		}
		$table1="cart";
		$class->insert($table1,$data1);
		//Lấy dữ liệu tu oder_details úp qua cart_details
		$sqloder2="Select * from oder_details where oder_id=$idoder";
		$dataoder2=$class->query($sqloder2);
		$table2="cart_details";
		foreach($dataoder2 as $key=>$value){
			$data2=array('cart_id' => $num+1,
						'product_id' => $value['product_id'],
						'price' => $value['price'],
						'amout' => $value['amout'],
						);
			$class->insert($table2,$data2);
		}
		//update số lượng sản phẩm sau khi giao
		$table3="product";
		foreach($dataoder2 as $key=>$value){
			$amout=$value['amout'];
			$data3="amout = amout - $amout";
			$class->updateAmoutOfProduct($table3,$data3,$value['product_id']);
		}
		
		
		foreach($dataoder as $value){
			$user=$value['user_username'];
			$sqltotal="select sum(total_price) as total from cart where user_username='".$user."'";
			$data3=$class->query($sqltotal);
			foreach ($data3 as $k){
				$total=$k['total'];
				break;
			}
		}
		//echo $total;
		$loaikh=array("lkh4","lkh3","lkh2","lkh1");
		foreach ($loaikh as $key){
			$sqlloaikh="select dieukien from loaikhachhang where malkh='$key'";
			$data4=$class->query($sqlloaikh);
			foreach ($data4 as $value){
				$dk=$value['dieukien'];
				break;
			}
			if($total>=$dk) {
				$table3='xephangthanhvien';
				$id=$user;
				$class -> updateXepHangTV($key,$id);
				//echo $key;
				//echo $id;
				break;
			}		
		}
		echo "Đã giao hàng thành công.";
		exit;
	}
?>