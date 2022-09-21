<?php
header('Content-Type: application/json; charset=utf-8');

// API vars
$device_name = '';
$link = '';
$zips_dir_name = 'zips/';
$zips_list = array();
$explode_by = '-';
$current_date = date("yyymd");

// Get Device Name.
if (isset($_GET['d'])) {
    $device_name = $_GET['d'];
    // Program to display URL of current page.
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') $link = "https";
    else $link = "http";

    // Here append the common URL characters.
    $link .= "://";

    // Append the host(domain name, ip) to the URL.
    $link .= $_SERVER['HTTP_HOST'];

    // Append the requested resource location to the URL
    $link .= '/' . basename(__DIR__) . '/' . $zips_dir_name;

    // Loop Through all the zip files in the zips/ dir

    foreach (new DirectoryIterator($zips_dir_name) as $file) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        $file_name_parts = explode($explode_by, $file);
        $zip_file_url = $link . $file;
        $md5sum_file = $file.'.md5sum';
        $changelog_file = $zip_file_url.".txt";
        if(!contains('.md5sum', $file)) {
            if(!contains('.txt', $file) ) {
                if(contains($device_name, $file)) {
                    $zips_list1 = Array(
                        'datetime' => time("s"),
                        'filename' => basename($zip_file_url),
                        'id' =>  md5(file_get_contents($zips_dir_name.$md5sum_file)),
                        'romtype' => $file_name_parts[3],
                        'size' => filesize($zips_dir_name.$file_name),
                        'url' => $zip_file_url,
                        'changelog' => $changelog_file,
                        'version' => $file_name_parts[1],
                    );
                    array_push($zips_list, $zips_list1);
                } else {
                    continue;
                }
            } else {
                continue;
            }
        } else {
            continue;
        }
    }
} else {
}

$array = Array('response' => $zips_list);

// encode array to json
$json = json_encode($array);
echo "$json";

function contains($needle, $haystack)
{
    return strpos($haystack, $needle) !== false;
}

function getFileNameFormURL($needle, $haystack)
{
    return preg_replace($needle, "", $haystack);
}
?>
