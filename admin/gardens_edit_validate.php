<?php

declare(strict_types = 1);

if (session_status() === PHP_SESSION_ACTIVE)
{
    \Safe\session_abort();
}

error_reporting(E_ALL);

const __ROOT__ = __DIR__ . DIRECTORY_SEPARATOR . '..';

require_once __ROOT__ . DIRECTORY_SEPARATOR .
    'utils' . DIRECTORY_SEPARATOR .
    'autoload.php';

require_once root_path('conf/config.inc.php');
include root_path('vendor/autoload.php');

$garden = garden_by_id(intval($_POST['id']));

$EXPECTED_FIELDS = [
    'name' => [
        'required' => true,
        'max_length' => 30,
        'is_number' => false
    ],
    'lat' => [
        'required' => true,
        'is_number' => true,
        'float' => true
    ],
    'lat' => [
        'required' => true,
        'is_number' => true,
        'float' => true
    ],
    'street-name' => [
        'required' => false,
        'max_length' => 80,
        'is_number' => false
    ],
    'street-num' => [
        'required' => true,
        'is_number' => true,
        'float' => true
    ],
    'size' => [
        'required' => true,
        'is_number' => true,
        'float' => true
    ]
];

foreach ($EXPECTED_FIELDS as $field => $field_req)
{
    if (!isset($_POST[$field]) && $field_req['required'])
    {
        echo ErrorTypes::MISSING_FIELD, $field;
        
        
    } else if ((strlen($_POST[$field]) > $field_req['max_length']) && ($field_req['is_number'] === false))
    {
        echo ErrorTypes::FIELD_TOO_LONG, $field;
        
        
    } 
    
    if (
        $field_req['is_number'] &&
        (($field_req['float'] && floatval($_POST[$field]) == 0 && !is_numeric($_POST[$field])) ||
        (intval($_POST[$field]) === 0 && !is_numeric($_POST[$field])))
    )
    {
        echo ErrorTypes::BAD_FORMAT, $field;
        
        
    }
}

$db = db_connect();

$lat = floatval($_POST['lat']);
$long = floatval($_POST['long']);
$street_num = intval($_POST['street-num']);
$size = intval($_POST['size']);
$user_id = intval($user['user_id']);
$garden_id = intval($garden['garden_id']);

try
{
    $stmt = $db->prepare('UPDATE `gardens`
    SET
        `garden_name` = :name_,
        `garden_lat` = :lat,
        `garden_long` = :long,
        `garden_street_name` = :street_name,
        `garden_street_number` = :street_num,
        `garden_size` = :size_,
        `garden_is_added_by_user` = TRUE,
        `_user_id` = :user_id
    WHERE
        `garden_id` = :garden_id
    ');
    $stmt->bindParam(':name_',       $_POST['name']);
    $stmt->bindParam(':lat',         $lat);
    $stmt->bindParam(':long',        $long);
    $stmt->bindParam(':street_name', $_POST['street-name']);
    $stmt->bindParam(':street_num',  $street_num, PDO::PARAM_INT);
    $stmt->bindParam(':size_',       $size,       PDO::PARAM_INT);
    $stmt->bindParam(':user_id',     $user_id,    PDO::PARAM_INT);
    $stmt->bindParam(':garden_id',   $garden_id,  PDO::PARAM_INT);

    $stmt->execute();

    
    

} catch (PDOException $err)
{
    \Safe\error_log($err->__toString());
    echo ErrorTypes::SQL_ERROR;
    
    
}

header('Location: /admin/gardens.php');
exit;
