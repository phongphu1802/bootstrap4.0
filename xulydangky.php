<?php
include('kiemtrasession.php');
xulythemtaikhoan();

	function xulythemtaikhoan()
	{
		if(isset($_POST['username']))
		{
			$user=$_POST['username'];
			include('connect.php');
			$class=new Database();
			$sq1=$class->connect();
			$sql="SELECT username FROM user WHERE username='$user'";
			$query=mysqli_query($sq1,$sql);
			if(mysqli_num_rows($query)==1){
				echo "Tên tài khoản đã tồn tại vui lòng đổi tên tài khoản khác.";
				exit;
			}
			else{
				if(isset($_POST['username']))
				{
					//bảng user
					$data=array('username' => $_POST['username'],
								'password' => $_POST['password'],
								'phonenumber'=> $_POST['phone'],
								'name' => $_POST['name'],
								'gioitinh'=> $_POST['sex'],
								'email'=> $_POST['email'],
								'address'=> $_POST['address'],
								'created'=> date('y-m-d'),
								'position'=>$_POST['position'],
								'ngaysinh'=>$_POST['date'],
								'chucvu'=>'cv0'
								);
					$table='user';
					$class->insert($table,$data);
					//bảng xephangthanhvien
					$data2=array('matv' => $_POST['username'],
								'malkh'=>'lkh1'
								);
					$table2='xephangthanhvien';
					$class->insert($table2,$data2);
					echo "Bạn đã đăng ký thành công.";
					$_SESSION['username']=$user;
					exit;
				}
			}		
		}
	}
?>