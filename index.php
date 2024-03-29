<?php
session_start();

function isAuthenticated()
{
	return isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated'] == 1;
}

?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <title>Test</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,700">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap-responsive.min.css">

<script type="text/javascript">
    var serverLog = function() {};
    <!-- IE workaround, in case that vendor libs use console.log -->
    if (typeof console !== 'object') console = {};
    if (typeof console.log === 'undefined') console.log = function() {};
    if (typeof console.info === 'undefined') console.info = function() {};
</script>

</head>
<body>

<div class="navbar" style="<?php if (!isAuthenticated()) echo "display:none"?>">
  <div class="navbar-inner">
    <div class="container">
      <a class="brand" href="#">Project name</a>
        <ul class="nav pull-right">
          <li><a id="admin-button"  href="#admin">Admin</a></li>
        </ul>
    </div>
  </div>
</div>

 <div class="container">

      <form class="form-signin" style="<?php if (isAuthenticated()) echo "display:none"?>" >
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text" class="input-block-level" placeholder="Email address">
        <input type="password" class="input-block-level" placeholder="Password">
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label>
        <button class="btn btn-large btn-primary" type="submit">Sign in</button>
        <div class="form-signin-msg" style="display:none">Unbekannter Benutzername oder falsches Passwort.</div>
      </form>
    </div> <!-- /container -->


<div id="content" style="<?php if (!isAuthenticated()) echo "display:none"?>">
<h1>Bootstrap starter template</h1>
  <p>Use this document as a way to quick start any new project.<br> All you get is this message and a barebones HTML document.</p>
</div>

<div id="admin" style="display:none">
    <div class="row">
        <div class="span2">
            <ul class="nav nav-list">
                <li class="nav-header">Benutzerverwaltung</li>
                <li><a href="#">Benutzerliste</a></li>
                <li><a href="#">Neuen Benutzer hinzufügen</a></li>
            </ul>
        </div>
        <div class="span10">

            <legend>Benutzerliste</legend>
            <table class="table table-striped users-table">
                <thead>
                    <tr>
                        <th>#</th> <th>Login</th> <th>Vorname</th> <th>Nachname</th>
                        <th>E-Mail</th> <th>Berechtigungen</th> <th>Flags</th> <th>Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="users-table-template">
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    </tr>
                </tbody>
            </table>

            <form class="form-add-user">
              <fieldset>
                <legend>Neuen Benutzer anlegen</legend>

                <label>Login</label>
                <input id="form-add-user-login" type="text">
                <label>Vorname</label>
                <input id="form-add-user-firstname" type="text">
                <label>Nachname</label>
                <input id="form-add-user-surname" type="text">
                <label>E-Mail</label>
                <input id="form-add-user-email" type="text">

                <label class="checkbox">
                  <input id="form-add-user-isadmin" type="checkbox">Benutzer ist Administrator
                </label>
                <button type="submit" class="btn">Hinzufügen</button>
                <div class="form-add-user-msg" style="display:none">Bitte fuellen Sie das Formular komplett aus.</div>
              </fieldset>
            </form>

        </div>
    </div>
</div>

<script data-main="js/main" src="vendor/require.js"></script>
</body>
</html>
