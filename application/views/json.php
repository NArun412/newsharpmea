<?php
if(isset($return)) {
	header('Content-Type: application/json');
	die( json_encode($return) );
}