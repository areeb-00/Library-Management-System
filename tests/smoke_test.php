<?php
// Run with: php tests/smoke_test.php
require_once __DIR__.'/../app/core/helpers.php';
$checks=['HTML escaping'=>e('<script>')==='&lt;script&gt;','Money formatting'=>money(8)==='₹8.00','CSRF token length'=>strlen(bin2hex(random_bytes(16)))===32,'Fine date arithmetic'=>max(0,(int)(new DateTimeImmutable('2026-11-01'))->diff(new DateTimeImmutable('2026-11-05'))->format('%r%a'))===4];
$failed=0;foreach($checks as $name=>$ok){echo ($ok?'PASS':'FAIL')." - {$name}
";if(!$ok)$failed++;}exit($failed?1:0);
