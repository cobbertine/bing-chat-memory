<?php

///// Setup required:

define('DB_HOST', getenv('DB_HOST'));
define('DB_USERNAME', getenv('DB_USERNAME'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_NAME', getenv('DB_NAME'));
define('DELETE_PASSWORD', getenv('DELETE_PASSWORD'));

/////


function set_code_exit($code)
{
    http_response_code($code);
    exit();
}

// Executes SQL with prepared queries & returns result.
// Bind values are supplied as an array here, which are converted to varargs with the splat (...) operator when calling bind_param
function execute_sql($sql, $type_string='', $bind_vals_array=[], $throw_error=False)
{
    $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno)
    {
        set_code_exit(500);
    }

    $prepared_statement = $mysqli->prepare($sql);

    if(count($bind_vals_array) > 0)
    {
        $prepared_statement->bind_param($type_string, ...$bind_vals_array);
    }

    $prepared_statement->execute();
    $errno = $prepared_statement->errno;
    $response = $prepared_statement->get_result();
    $prepared_statement->close();
    $mysqli->close();

    // All non SELECT queries respond with False. Must determine if an error has occurred with errno. A non-zero errno is bad.
    if($response === False && $errno !== 0)
    {
        if($throw_error === False)
        {
            set_code_exit(500);
        }
        else
        {
            throw new Exception('SQL Error');
        }
    }

    return $response;
}

if (isset($_GET['store']))
{
    $hexText = $_GET['store'];
    $hexTextArray = explode("00", $hexText);

    foreach($hexTextArray as $splitHexText)
    {
        if(strlen($splitHexText) > 0)
        {
            if(strlen($splitHexText) % 2 !== 0)
            {
                $splitHexText = substr($splitHexText, 0, strlen($splitHexText)-1);
            }

            echo "Saving..." . $splitHexText . "<br>";
            execute_sql('INSERT INTO memory_bank (message_text) VALUES (?)', 's', [$splitHexText]);
        }
    }

    echo 'Message saved';
    exit();
}

if (isset($_GET['read']))
{
    $empty_db_message = "No messages saved at this time...\n";
    $decoded_messages = "";
    $response = execute_sql('SELECT message_text FROM memory_bank');

    while(TRUE)
    {
        $row = $response->fetch_assoc();

        if($row !== null)
        {
            $decoded_messages = $decoded_messages . hex2bin($row['message_text']) . "\n";
        }
        else
        {
            break;
        }
    }

    if(strlen($decoded_messages) === 0)
    {
        $decoded_messages = $empty_db_message;
    }

    echo $decoded_messages;
}

if (isset($_GET['delete']))
{
    $password = $_GET['delete'];

    if($password === DELETE_PASSWORD)
    {
        execute_sql('DELETE FROM memory_bank');
        exit();
    }
}