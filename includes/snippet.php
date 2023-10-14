<?php

function cleanInput($input) {

  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );

    $output = preg_replace($search, '', $input);
    return $output;
  }
?>

<?php
function sanitize($input) {
$dbhost = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "library_db";
$link = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        // if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        // }
        $input  = cleanInput($input);
        $output = mysqli_real_escape_string($link, $input);
    }
    return $output;
}

function upload_image($image_array, $min_size=5000)
    {
        $img_size = $image_array['size'];
        $tmp_name = $image_array['tmp_name'];
        $img_name = $image_array['name'];
        $img_type = $image_array['type'];

        if ($img_size > 921600) {
            $err_flag = true;
        }

        if ($img_size < $min_size) {
            $err_flag = true;
        }
        

        $safe_ext = array("jpg", 'jpeg', 'png', 'gif');
        $img_ext2 = explode('/', $img_type);
        $img_ext3 = end($img_ext2);
        $img_ext = strtolower($img_ext3);
        if (!(in_array($img_ext, $safe_ext))) {
            $err_flag = true;
        }

        $upload_dir = 'library-images/';
        $img_path = $upload_dir.'license'.time().mt_rand(10,99).'.'.$img_ext;

    if (!isset($err_flag)) {
        $send = move_uploaded_file($tmp_name, $img_path);
        if ($send) {
            return $img_path;
        } else {
            return false;
        }
    }
}