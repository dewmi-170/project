<?php
// Just run this once to get the hashed password
echo password_hash("admin123", PASSWORD_DEFAULT) ."<br>";

echo password_hash("stock123", PASSWORD_DEFAULT) ."<br>";
echo password_hash("cash123", PASSWORD_DEFAULT) ."<br>";
echo password_hash("supplier123", PASSWORD_DEFAULT)."<br>";


?>