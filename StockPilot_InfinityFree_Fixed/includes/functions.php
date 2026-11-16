<?php
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function csrf_token(){ if(empty($_SESSION['csrf_token'])) $_SESSION['csrf_token']=bin2hex(random_bytes(32)); return $_SESSION['csrf_token']; }
function csrf_field(){ return '<input type="hidden" name="csrf_token" value="'.e(csrf_token()).'">'; }
function verify_csrf(){ if(!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token']??'', $_POST['csrf_token'])){ die('Invalid CSRF token. Please go back and try again.'); } }
function clean($s){ return trim((string)$s); }
function money($n){ return '₦'.number_format((float)$n,2); }
function redirect($url){ header("Location: $url"); exit; }
function audit_log($pdo,$action,$module,$details=''){
  try{ $st=$pdo->prepare("INSERT INTO audit_logs(user_id,action,module,details,ip_address) VALUES(?,?,?,?,?)"); $st->execute([$_SESSION['user_id']??null,$action,$module,$details,$_SERVER['REMOTE_ADDR']??'']); }catch(Exception $ex){}
}
function notify_low_stock($pdo,$product_id){
  $st=$pdo->prepare("SELECT p.name,p.low_stock_threshold, COALESCE(SUM(s.quantity),0) as total FROM products p LEFT JOIN stock s ON s.product_id=p.id WHERE p.id=? GROUP BY p.id");
  $st->execute([$product_id]); $r=$st->fetch();
  if($r && $r['total'] <= $r['low_stock_threshold']){
    $chk=$pdo->prepare("SELECT id FROM notifications WHERE related_id=? AND title='Low Stock Alert' AND created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)"); $chk->execute([$product_id]);
    if(!$chk->fetch()){ $ins=$pdo->prepare("INSERT INTO notifications(title,message,type,related_id) VALUES(?,?,?,?)"); $ins->execute(['Low Stock Alert',"Product {$r['name']} is low on stock ({$r['total']} left, threshold {$r['low_stock_threshold']}).",'warning',$product_id]); }
  }
}
function get_stock_total($pdo,$pid){ $s=$pdo->prepare("SELECT COALESCE(SUM(quantity),0) FROM stock WHERE product_id=?"); $s->execute([$pid]); return (int)$s->fetchColumn(); }
function adjust_stock($pdo,$product_id,$warehouse_id,$qty_change,$type,$reference,$notes=''){
  // qty_change can be + or - ; caller must be inside transaction if needed
  $st=$pdo->prepare("INSERT INTO stock(product_id,warehouse_id,quantity) VALUES(?,?,?) ON DUPLICATE KEY UPDATE quantity=quantity+VALUES(quantity)");
  $st->execute([$product_id,$warehouse_id,$qty_change]);
  $m=$pdo->prepare("INSERT INTO stock_movements(product_id,warehouse_id,movement_type,quantity,reference,notes,created_by) VALUES(?,?,?,?,?,?,?)");
  $m->execute([$product_id,$warehouse_id,$type,$qty_change,$reference,$notes,$_SESSION['user_id']??null]);
  notify_low_stock($pdo,$product_id);
}
function gen_no($prefix){ return $prefix.'-'.date('Ymd').'-'.strtoupper(substr(bin2hex(random_bytes(3)),0,6)); }
