<?php

/**
 * Created by PhpStorm.
 * User: blaze
 * Date: 3/26/2017
 * Time: 6:17 PM
 */

define('API_MASTER_KEY', 'key');
define('HOST_NAME', 'localhost');
define('USER_NAME', 'root');
define('DB_PASSWORD', 'pass');
define('DB_NAME', 'experiment119c');
include 'models/ResponseModel.php';

class LoginSystem
{
    private $pdo = null;

    /**
     * LoginSystem constructor.
     * Initialize PDO database object
     */
    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=' . HOST_NAME . ';dbname=' . DB_NAME, USER_NAME, DB_PASSWORD);
    }

    /**
     * @param $username
     * @param $password
     * Query the database for the supplied information and act accordingly.
     */
    public function login($username, $password)
    {
        if ($this->verifyRequest($_GET['experiderek101'])) {
            $state = $this->pdo->prepare('SELECT * FROM users WHERE username = :user AND BINARY password = :pass');
            $state->bindParam(':user', $username);
            $state->bindParam(':pass', $password);
            if ($state->execute()) {
                $results = $state->fetch(PDO::FETCH_ASSOC);
                if ($results) {
                    $this->response(200, 'Login successful', 'Login successful', null);
                    $this->onLoginSuccess($username);
                } else {
                    $this->response(403, 'Login failed', 'Login failed', null);
                    $this->onLoginFailure($username);
                    //TODO: Do stuff for failed login
                }
            } else {
                $this->response(204, 'Failed to execute db query', 'Failed to execute db query', null);
            }
        }
    }

    /**
     * @param $status http status code
     * @param $message
     * @param $explain is an explanation for why X has occurred
     * @param $data is for special data (e.g images, etc, I think)
     * Echos a json response with the following data.
     */
    public function response($status, $message, $explain, $data)
    {
        $response = new ResponseModel($status, $message, $explain, $data);
        echo json_encode($response, JSON_PRETTY_PRINT) . '<br>';
    }

    /**
     * @param $username
     * Updates the last login time for given user
     * Does not require authentication and should only be used
     * when the information is already authenticated
     */
    private function updateLastLogin($username)
    {
        $currentTime = time();
        $state = $this->pdo->prepare("UPDATE users SET last_login = :login WHERE username = :user");
        $state->bindParam(':login', $currentTime);
        $state->bindParam(':user', $username);
        if ($state->execute()) {
            $this->response(200, 'Entry updated successfully', 'Entry updated successfully', null);
        } else {
            $this->response(204, 'Entry failed to update', 'Entry failed to update', null);
        }
    }

    public function onLoginSuccess($username)
    {
        $this->updateLastLogin($username);
    }

    public function onLoginFailure($username)
    {

    }

    /**
     * @param $key supplied API key
     * @return bool
     * Verifies API KEY is correct.
     */
    public function verifyRequest($key)
    {
        if ($key != API_MASTER_KEY) {
            $this->OnInvalidKey();
            return false;
        } else {
            return true;
        }
    }

    public function OnInvalidKey()
    {
        //TODO: Create a database for denied access attempts and save the IP
        $this->response(403, 'Access denied ' . $_SERVER['REMOTE_ADDR'], 'Unauthorized', null);
    }
}