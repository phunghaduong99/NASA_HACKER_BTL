<?php
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/database.php');
	include_once ($filepath.'/../helpers/format.php');

?>

<?php
	/**
	 * 
	 */
	class User
	{
		private $db;
		private $fm;
		public function __construct()
		{
//			$this->db= new Database();
//			$this->fm= new Format();
		}

		public function insert_customers($data){
			$name= mysqli_real_escape_string($this->db->link, $data['name']);
			$username= mysqli_real_escape_string($this->db->link, $data['username']);
			$email= mysqli_real_escape_string($this->db->link, $data['email']);
			$password= mysqli_real_escape_string($this->db->link, md5($data['password']));
			if($name=="" ||$username=="" ||$email=="" || $password=="" ){
				$alert = "<span >Vui lòng điền đầy đủ thông tin</span>";
				return $alert;
			}else{
				$check_email= "SELECT * From tbl_customer Where email ='$email' limit 1 ";
				$result_check = $this->db->select($check_email);
				if($result_check){
					$alert ="<span >Email đã tồn tại</span>";
					return $alert;
				}else{
					$query = "INSERT INTO tbl_customer(name,username,email,password) VALUES('$name','$username','$email','$password')";
				$result = $this->db->insert($query);
				if($result){
					$alert = "<span >Đăng kí thành công</span>";
					return $alert;
				}else{
					$alert = "<span >Đăng kí không thành công</span>";
					return $alert;
				}
				}
			}
		}
		public function login_customers($data){
			$email= mysqli_real_escape_string($this->db->link, $data['email']);
			$password= mysqli_real_escape_string($this->db->link, md5($data['password']));
			if($email=="" ||$password==""){
				$alert = "<span class='error'>Vui lòng điền đầy đủ thông tin</span>";
				return $alert;
			}else{
				$check_login= "SELECT * From tbl_customer Where email ='$email' and password='$password'";
				$result_check = $this->db->select($check_login);
				if($result_check){
					$value = $result_check->fetch_assoc();
					Session::set('customer_login',true);
					Session::set('customer_id',$value['id']);
					Session::set('customer_name',$value['name']);
					header('Location:index.php');
				}else{
					$alert ="<span class='error'>Email hoặc Password không chính xác</span>";
					return $alert;
				}
			}
		}
		public function show_customers($id){
			$query= "SELECT * From tbl_customer Where id ='$id'";
			$result = $this->db->select($query);
			return $result;
		}
	}
?>