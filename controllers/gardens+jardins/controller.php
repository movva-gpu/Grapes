<?php

function index(): void {
    if (!isset($GLOBALS['user']))
    {
        set_session_error(ErrorTypes::NOT_LOGGED_IN, line: __LINE__);
        header('Location: /login');
        exit;
    }

    $view_name = 'gardens/Gardens';
    $page_title = 'Jardins participatifs';

    $GLOBALS['user_gardens'] = gardens_by_user_id($GLOBALS['user']['user_id']);
    $GLOBALS['gardens']      = get_gardens();
    $GLOBALS['users']        = get_users();

    $head =
    '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    >';

    require views_path('templates/Main_template.php');
}

function add(): void
{
    if (!isset($GLOBALS['user']))
    {
        set_session_error(ErrorTypes::NOT_LOGGED_IN, line: __LINE__);
        header('Location: /login');
        exit;
    }

    $view_name = 'gardens/Add';
    $page_title = 'Ajouter un jardin';

    require views_path('templates/Main_template.php');
}

function validate(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST))
    {
        header('Location: /400');
        exit;
    }

    $uuid = decrypt($_POST['uuid']);
    $user = user_by_uuid($uuid);

    if (!$user)
    {
        set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
        header('Location: /gardens/add');
        exit;
    }

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
        'long' => [
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
        ],
        'n-plots' => [
            'required' => true,
            'is_number' => true,
            'float' => true
        ]
    ];

    foreach ($EXPECTED_FIELDS as $field => $field_req)
    {
        if (!isset($_POST[$field]) && $field_req['required'])
        {
            set_session_error(ErrorTypes::MISSING_FIELD, $field, line: __LINE__);
            header('Location: /gardens/add');
            exit;
        } else if ((strlen($_POST[$field]) > $field_req['max_length']) && ($field_req['is_number'] === false))
        {
            set_session_error(ErrorTypes::FIELD_TOO_LONG, $field, line: __LINE__);
            header('Location: /gardens/add');
            exit;
        } 
        
        if (
            $field_req['is_number'] &&
            (($field_req['float'] && floatval($_POST[$field]) == 0 && !is_numeric($_POST[$field])) ||
            (intval($_POST[$field]) === 0 && !is_numeric($_POST[$field])))
        )
        {
            set_session_error(ErrorTypes::BAD_FORMAT, $field, line: __LINE__);
            header('Location: /gardens/add');
            exit;
        }
    }

    $db = db_connect();

    $name = htmlentities($_POST['name']);
    $lat = $_POST['lat'];
    $long = $_POST['long'];
    $street_name = htmlentities($_POST['street-name']);
    $street_num = $_POST['street-num'];
    $size = $_POST['size'];
    $n_plots = $_POST['n-plots'];
    $user_id = $user['user_id'];

    try
    {
        $stmt = $db->prepare('INSERT INTO `gardens`(
            `garden_name`,
            `garden_lat`,
            `garden_long`,
            `garden_street_name`,
            `garden_street_number`,
            `garden_size`,
            `garden_n_plots`,
            `garden_is_added_by_user`,
            `_user_id`
        ) VALUES(
            :name_,
            :lat,
            :long,
            :street_name,
            :street_num,
            :size_,
            :n_plots,
            TRUE,
            :user_id
        );');
        $stmt->bindParam(':name_', $name);
        $stmt->bindParam(':lat', $lat);
        $stmt->bindParam(':long', $long);
        $stmt->bindParam(':street_name', $street_name);
        $stmt->bindParam(':street_num', $street_num);
        $stmt->bindParam(':size_', $size);
        $stmt->bindParam(':n_plots', $n_plots);
        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();

        header('Location: /gardens');
        exit;

    } catch (PDOException $err)
    {
        error_log($err);
        set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
        header('Location: /gardens/add');
        exit;
    }
}

function edit(): void
{
    if (!isset($GLOBALS['user']))
    {
        set_session_error(ErrorTypes::NOT_LOGGED_IN, line: __LINE__);
        header('Location: /inscription');
        exit;
    }

    if (!isset($_GET['id']))
    {
        header('Location: /400');
        exit;
    }

    $GLOBALS['garden'] = garden_by_id($_GET['id']);

    if ($GLOBALS['garden'] === false)
    {
        set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    $view_name = 'gardens/Edit';
    $page_title = 'Modifier un jardin';

    require views_path('templates/Main_template.php');
}

function validateedit(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST))
    {
        header('Location: /400');
        exit;
    }

    $uuid = decrypt($_POST['uuid']);
    $user = user_by_uuid($uuid);

    if (!$user)
    {
        set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
        header('Location: /gardens/edit?id=' . decrypt($_POST['id']));
        exit;
    }

    $garden_id = decrypt($_POST['id']);
    $garden = garden_by_id($garden_id);

    if (!$garden)
    {
        set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
        header('Location: /gardens/edit?id=' . decrypt($_POST['id']));
        exit;
    }

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
        'long' => [
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
            set_session_error(ErrorTypes::MISSING_FIELD, $field, line: __LINE__);
            header('Location: /gardens/edit?id=' . decrypt($_POST['id']));
            exit;
        } else if ((strlen($_POST[$field]) > $field_req['max_length']) && ($field_req['is_number'] === false))
        {
            set_session_error(ErrorTypes::FIELD_TOO_LONG, $field, line: __LINE__);
            header('Location: /gardens/edit?id=' . decrypt($_POST['id']));
            exit;
        } 
        
        if (
            $field_req['is_number'] &&
            (($field_req['float'] && floatval($_POST[$field]) == 0 && !is_numeric($_POST[$field])) ||
            (intval($_POST[$field]) === 0 && !is_numeric($_POST[$field])))
        )
        {
            set_session_error(ErrorTypes::BAD_FORMAT, $field, line: __LINE__);
            header('Location: /gardens/edit?id=' . decrypt($_POST['id']));
            exit;
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

        header('Location: /gardens');
        exit;

    } catch (PDOException $err)
    {
        error_log($err);
        set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
        header('Location: /gardens/edit?id=' . decrypt($_POST['id']));
        exit;
    }
}

function delete(): void {
    $uuid = decrypt($_GET['user_uuid']);
    $user = user_by_uuid($uuid);

    if (!$user)
    {
        set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    $garden_id = $_GET['id'];
    $garden = garden_by_id($garden_id);

    if (!$garden)
    {
        set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    if ($garden['_user_id'] === $user['user_id'])
    {
        garden_by_id($garden['garden_id'], action: DBActions::DELETE);
        header('Location: /gardens');
        exit;
    }
    
    set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
    header('Location: /gardens');
    exit;
}
