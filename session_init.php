<?php
//memulai session dengan cookie yang aman:
//httponly (tidak terbaca JavaScript), SameSite=Lax (tidak ikut dikirim dari situs lain), secure jika memakai HTTPS
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        "lifetime" => 0,
        "path" => "/",
        "httponly" => true,
        "samesite" => "Lax",
        "secure" => !empty($_SERVER["HTTPS"]),
    ]);
    session_start();
}
