<?php
$env = parse_ini_file(".env");
$alias = $env["DB_TNS"] ?? "";
$user = $env["DB_USERNAME"] ?? "";
$pass = $env["DB_PASSWORD"] ?? "";
echo "Testing alias from .env: $alias" . PHP_EOL;
$conn = @oci_connect($user, $pass, $alias, "AL32UTF8");
if (!$conn) {
    echo "FAILED" . PHP_EOL;
    var_dump(oci_error());
    exit(1);
}
$stmt = oci_parse($conn, "select 1 as test_value from dual");
oci_execute($stmt);
$row = oci_fetch_assoc($stmt);
var_dump($row);
echo "OCI8 SUCCESS" . PHP_EOL;
