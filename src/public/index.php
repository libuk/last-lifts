<?php

declare(strict_types=1);

session_start();

define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);

require_once ROOT . 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOT);
$dotenv->load();

function prettyPrint(mixed $value): void
{
  echo '<pre>';
  print_r($value);
  echo '</pre>';
}

if ($_SERVER['REQUEST_URI'] === '/login' && $_SERVER['REQUEST_METHOD'] === 'GET') {
  # create a random token to be compared against when the POST request is made.
  # this helps guard against cross-site request forgery.
  $token = bin2hex(random_bytes(35));
  $_SESSION['token'] = $token;

  $html = <<<HTML
    <DOCTYPE html>
    <html>
    <body>
      <h1>Login</h1>
      <form method="POST" action="/login" id="login-form">
        <input type="hidden" name="token" value="$token" />
        <label for="email">Email:</label>
        <input id="email" name="email" type="text" placeholder="someone@email.com" required />
        <label for="password">Password:</label>
        <input id="password" name="password" type="password" required />
        <input type="submit" value="submit" />
      </form>
    </body>
    </html>
  HTML;

  echo $html;
  exit();
}

if ($_SERVER['REQUEST_URI'] === '/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  # ensure the token from the form matches the one in our session
  # this is to guard against CSRF attacks
  if (!($_POST['token'] === $_SESSION['token'])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');

    echo "Access denied.";
    die();
  }

  try {
    $dbType = $_ENV['DB_TYPE'];
    $dbHost = $_ENV['DB_HOST'];
    $dbName = $_ENV['DB_NAME'];
    $dbUser = $_ENV['DB_USER'];
    $dbPass = $_ENV['DB_PASS'];
    $dbCharset = $_ENV['DB_CHARSET'];

    $cleanedEmail = htmlspecialchars($_POST['email']);

    $dbh = new PDO("{$dbType}:host={$dbHost};dbname={$dbName};charset={$dbCharset}", $dbUser, $dbPass);
    $query = $dbh->prepare("SELECT user_name, password_hash FROM lifters WHERE email = ?");
    $query->execute([$cleanedEmail]);

    $result = $query->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
      header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized');

      echo "Login failed.";
      die();
    }

    if (!password_verify($_POST['password'], $result['password_hash'])) {
      header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized');

      # TODO: remove password specific feedback before deploying to production.
      echo "Login password failed.";
      die();
    }

    header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');

    $name = $result['user_name'];

    $html = <<<HTML
      <DOCTYPE html>
      <html>
      <body>
        <h1>Hello, $name</h1>
      </body>
      </html>
    HTML;

    echo $html;
    exit();

  } catch(PDOException $e) {
    die("Error: " . $e->getMessage());
  }
}
