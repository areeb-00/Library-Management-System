<?php
declare(strict_types=1);
$root=dirname(__DIR__);
if (is_file($root.'/.env')) { foreach(file($root.'/.env', FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) as $line) { $line=trim($line); if($line===''||str_starts_with($line,'#')||!str_contains($line,'=')) continue; [$k,$v]=explode('=',$line,2); $v=trim($v); if(strlen($v)>=2 && (($v[0]==='"'&&str_ends_with($v,'"'))||($v[0]==="'"&&str_ends_with($v,"'")))) $v=substr($v,1,-1); if(getenv(trim($k))===false) putenv(trim($k).'='.$v); } }
$debug=(getenv('APP_DEBUG')?:'false')==='true';
ini_set('display_errors',$debug?'1':'0'); error_reporting(E_ALL);
$secure=(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off');
session_set_cookie_params(['lifetime'=>0,'path'=>'/','secure'=>$secure,'httponly'=>true,'samesite'=>'Lax']);
session_start();
require_once __DIR__.'/config/database.php'; require_once __DIR__.'/core/helpers.php'; require_once __DIR__.'/core/Auth.php'; require_once __DIR__.'/services/LibraryService.php';
