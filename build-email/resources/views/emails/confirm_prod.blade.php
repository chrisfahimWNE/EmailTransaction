<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Confirm Email</title>
    </head>
    <body>
        <h1>This is the Prod Email</h1>
        <a href="http://localhost:8000/api/email/confirmed?token={{$token}}">
            <button>Confirm Email</button>
        </a>
    </body>
</html>
