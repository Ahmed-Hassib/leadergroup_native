<?php 
// get piece full name 
$full_name = $_GET['full_name'];
// check id if isset
$id = isset($_GET['id']) ? $_GET['id'] : '';
// create an object of Pieces class
$pcs_obj = new Pieces();
// query statement
$query = isset($_GET['id']) ? "SELECT COUNT(`full_name`) FROM `pieces_info` WHERE `full_name` LIKE ? AND `company_id` = ? AND `id` != ?"  : "SELECT COUNT(`full_name`) FROM `pieces_info` WHERE `full_name` LIKE ? AND `company_id` = ?";
// prepare statement
$stmt = $con->prepare($query);
$stmt->execute(isset($_GET['id']) ? array($full_name, $_SESSION['company_id'], $id) : array($full_name, $_SESSION['company_id']));
// get all rows
$result = $stmt->fetchColumn();

$is_exist_fullname = $result > 0 ? true :false;
// send the result as a json formate
echo json_encode(array($is_exist_fullname, $result));