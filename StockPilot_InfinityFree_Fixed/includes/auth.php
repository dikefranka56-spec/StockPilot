<?php
require_once __DIR__.'/db.php';
require_once __DIR__.'/functions.php';
function current_user(){ if(empty($_SESSION['user_id'])) return null; return ['id'=>$_SESSION['user_id'],'name'=>$_SESSION['full_name']??'','username'=>$_SESSION['username']??'','role'=>$_SESSION['role']??'','role_id'=>$_SESSION['role_id']??0]; }
function require_login(){ if(empty($_SESSION['user_id'])){ header("Location: ../index.php"); exit; } }
function can($pdo,$module,$action='can_view'){
  if(($_SESSION['role']??'')==='Administrator') return true;
  $st=$pdo->prepare("SELECT $action FROM permissions WHERE role_id=? AND module=?"); $st->execute([$_SESSION['role_id']??0,$module]); $r=$st->fetch(); return $r && (int)$r[$action]===1;
}
function require_perm($pdo,$module,$action='can_view'){ require_login(); if(!can($pdo,$module,$action)){ http_response_code(403); die('<h2 style="font-family:sans-serif;padding:40px">403 - You do not have permission to access this module.</h2><p><a href="dashboard.php">Back to Dashboard</a></p>'); } }
function login_user($pdo,$username,$password){
  $st=$pdo->prepare("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON r.id=u.role_id WHERE u.username=? AND u.is_active=1"); $st->execute([$username]); $u=$st->fetch();
  if($u && password_verify($password,$u['password'])){ $_SESSION['user_id']=$u['id']; $_SESSION['full_name']=$u['full_name']; $_SESSION['username']=$u['username']; $_SESSION['role']=$u['role_name']; $_SESSION['role_id']=$u['role_id']; $_SESSION['csrf_token']=bin2hex(random_bytes(32)); audit_log($pdo,'Login','auth',"User {$u['username']} logged in"); return true; } return false;
}
function logout_user($pdo){ audit_log($pdo,'Logout','auth','User logged out'); session_destroy(); }
