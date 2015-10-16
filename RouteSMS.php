<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

class RouteSMS
{
    /**
     * RouteSMS username
     *
     * @var $username
     */
    private $username;

    /**
     * RouteSMS password
     *
     * @var $password
     */
    private $password;

    /**
     * Guzzle client
     * @var $client
     */
    private $client;

    /**
     * Successful message
     */
    const SUCCESS = 1701;
    const INVALID_USERNAME_PASSWORD = 1703;
    const INVALID_TYPE = 1704; //Invalid value in "type" field
    const INVALID_MESSAGE = 1705;
    const INVALID_RECIPIENT = 1706;
    const INVALID_SENDER = 1707;
    const INVALID_DLR = 1708;
    const USER_VALIDATION_ERROR = 1709;
    const INTERNAL_ERROR = 1710;
    const INSUFFICIENT_CREDIT = 1025;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->client = new Client([
            'base_uri' => 'http://smsplus3.routesms.com:8080/bulksms/',
            'timeout' => 15
        ]);
    }

    /**
     * @param string $sender
     * @param int $recipient
     * @param string $message
     * @param int $type
     * @param int $dlr
     * @throws Exception
     */
    public function send($sender, $recipient, $message, $type=0, $dlr=1)
    {
        if (!$recipient || !is_numeric(trim($recipient))) {
            throw new Exception('Recipient is required and must be numeric');
        }

        if (!$sender || strlen($sender) > 18) {
            throw new Exception('Sender is required and must not exceed 11 characters');
        }

        if (!$message) {
            throw new Exception('Message is required');
        }

        if ($recipient && $sender && $message) {
            $fragment = [
                'username' => $this->username,
                'password' => $this->password,
                'type' => $type,
                'dlr' => $dlr,
                'destination' => trim($recipient),
                'source' => $sender,
                'message' => trim($message)
            ];

            $url = 'bulksms?' . http_build_query($fragment);
            try {

                $response = $this->client->request('GET', $url);
                $response = $response->getBody()->getContents();
                $result = explode('|', $response);

                switch ($result[0]) {
                    case self::SUCCESS:
                        echo 'Success';
                        break;
                    case self::INVALID_USERNAME_PASSWORD:
                        throw new Exception('Invalid username or password supplied');
                        break;
                    case self::INVALID_TYPE:
                        throw new Exception('Invalid type supplied.');
                        break;
                    case self::INVALID_MESSAGE:
                        throw new Exception('Invalid message. Message contains invalid characters');
                        break;
                    case self::INVALID_RECIPIENT:
                        throw new Exception('Invalid recipient. Recipient must be numeric');
                        break;
                    case self::INVALID_SENDER:
                        throw new Exception('Invalid sender. Sender must not be more than 11 characters');
                        break;
                    case self::INVALID_DLR:
                        throw new Exception('Invalid dlr supplied');
                        break;
                    case self::USER_VALIDATION_ERROR:
                        throw new Exception('User validation error');
                        break;
                    case self::INTERNAL_ERROR:
                        throw new Exception('Internal error');
                        break;
                    case self::INSUFFICIENT_CREDIT:
                        throw new Exception('Insufficient credit');
                        break;
                    default:
                        throw new Exception('An error occurred with code ' .  $result[0]);
                }

            } catch (TransferException $e) {
                throw new Exception('The following error occurred ' . $e->getMessage());
            }

        }
    }
}