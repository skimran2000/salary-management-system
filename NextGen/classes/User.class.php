<?php

class User{
	public static function loggedIn(){
		if(isset($_COOKIE['hrm-user']) && $_COOKIE['hrm-user'] != ""){
			return true; 
		}
		return false; 
	}

	public static function login($email, $password, $status){
		$query = Db::fetch("users", "token", "email = ? AND password = ? AND status = ?  ", array($email, $password, $status), "", "", "" ); 
		if(Db::count($query)){
			Messages::success("Login Succeeded");
			$tokenArray = Db::num($query); 
			$token = $tokenArray[0]; 

			// this cookie will be saved on the database for security purposes
			setcookie("hrm-user", $token ,time()+(60*60*24*7*30),"/", "","",TRUE);

			Config::redir("index.php"); 

			

			return; 
		}
		
		if($status == 1){
			$user = "an Employee";
		} else {
			$user = "an Admin"; 
		}

		Messages::error("Either your email or password is incorrect. <strong>WAIT</strong>, did you mean to login as $user? Please click <strong><a href='login.php'>HERE</a></strong> to log in as $user "); 
	}

	public static function get($token, $field){
		$query = Db::fetch("users", "$field", "token = ? ", $token, "", "", "" );
		$data = Db::num($query); 
		return $data[0]; 
	}

	public static function getbyid($eid, $field){
		$query = Db::fetch("users", "$field", "id = ? ", $eid, "", "", "" );
		$data = Db::num($query); 
		return $data[0]; 
	}
	
	public static function getToken(){
		if(self::loggedIn()){
			return $_COOKIE['hrm-user']; 
		}
		return ""; 
	}
	
	
	public static function profile($token){
		$pic = User::get($token, "profile");
		$img = (!empty($pic)) ? ($pic) : ("profile.png");
		$empid = User::get($token, "id");
		$userFirstName = User::get($token, "firstName");
		$userSecondName = User::get($token, "secondName");
		$userEmail = User::get($token, "email");
		$userPassword = User::get($token, "password");
		$userToken = User::get($token, "token");
		$userStatus = User::get($token, "status");
		$userPhone = User::get($token, "phone");
		$userProfile = User::get($token, "profile");
		$userGender = User::get($token, "gender");
		$userRole = User::get($token, "designation");
		
		if($userStatus == 1){
			$userRole = "Admin";
			$userSalary = "Admin";
		} else {
			$userRole = $userRole;
		} 
		echo "<div class='form-holder'>";
		
		$form = new Form(3, "post");
		$form->init();
		echo "
			<div class='form-group'>
				<label class='col-md-3'>Photo</label>
				<div class='col-md-9'>
					<img src='images/$img' width='70px' height='70px'> 
				</div> 
			</div> 
		";
		$form->textBox("Employee Id", "user-ei", "text",  $empid, array("readonly='readonly'", "  style='font-size: 17px;' ") );
		$form->textBox("First Name", "user-fn", "text",  $userFirstName, array("readonly='readonly'", "  style='font-size: 17px;' ") );
		$form->textBox("Last Name", "user-sn", "text",  $userSecondName, array("readonly='readonly'", "  style='font-size: 17px;' ") );
		$form->textBox("Email", "user-em", "text",  $userEmail, array("readonly='readonly'", "  style='font-size: 17px;' ") );
		$form->textBox("Designation", "user-role", "text",  $userRole, array("readonly='readonly'", "  style='font-size: 17px;' ") );
		$form->textBox("Gender", "user-gender", "text",  $userGender, array("readonly='readonly'", "  style='font-size: 17px;' ") );
		$form->textBox("Phone", "user-phone", "text",  $userPhone, array("readonly='readonly'", "  style='font-size: 17px;' ") );
		$form->close("");
		
		echo "</div>";
	}
	
	public static function changePassword($oldPassword, $newPassword){
		if(!self::loggedIn()){
			Messages::error("Login first!"); 
			return; 
		}
		$query = Db::fetch("users", "password", "token = ? ", self::getToken(), "", "", "");
		$dataCurrentPassword = Db::num($query); 
		$currentPassword = $dataCurrentPassword[0]; 
		if($currentPassword != $oldPassword){
			Messages::error("Your old password could not be found in the system"); 
			return; 
		}
		Db::update("users", array("password"), array($newPassword), "token = ? ", self::getToken()); 
		Messages::success("Your password has been updated"); 
	}
}