<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link rel="stylesheet" href="styles.css"> <!-- Optional: Link your CSS file here -->
</head>
<body>
  <div class="registration-container">
    <h2>Registration Form</h2>
    <form id="registration-form" action="/register" method="POST"> <!-- Changed action to "/register" for Node.js endpoint -->
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required><br><br>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required><br><br>
      <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.html">Login</a></p>
  </div>
</body>
</html>
