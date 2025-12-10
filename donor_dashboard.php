<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Redirect to login if not logged in or not donor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'donor') {
    header("Location: login.html");
    exit();
}

$name = htmlspecialchars($_SESSION['name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Donor Dashboard - Meal4All</title>
  <link rel="stylesheet" href="styles.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <header>
    <h1>Meal4All</h1>
    <nav>
      <a href="donations.php">Donations</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

  <main class="container mt-5">
    <h2 class="text-center mb-4">Welcome, <?php echo $name; ?>!</h2>
    <div class="dashboard-box p-4 shadow-sm rounded">
      <h3 class="mb-4">Submit a Donation</h3>
      <form action="submit_donation.php" method="POST" class="needs-validation" novalidate>
        <div class="mb-3">
          <label for="food_description" class="form-label">Food Description</label>
          <textarea id="food_description" class="form-control" name="food_description" required></textarea>
          <div class="invalid-feedback">Please provide a description of the food.</div>
        </div>
        <div class="mb-3">
          <label for="quantity" class="form-label">Quantity (in servings)</label>
          <input type="number" id="quantity" class="form-control" name="quantity" min="1" required />
          <div class="invalid-feedback">Please enter a valid quantity.</div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="date" class="form-label">Pickup Date</label>
            <input type="date" id="date" class="form-control" name="date" required />
            <div class="invalid-feedback">Please select a pickup date.</div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="time" class="form-label">Pickup Time</label>
            <input type="time" id="time" class="form-control" name="time" required />
            <div class="invalid-feedback">Please select a pickup time.</div>
          </div>
        </div>
        <div class="mb-3">
          <label for="contact" class="form-label">Contact Info</label>
          <input type="text" id="contact" class="form-control" name="contact" required />
          <div class="invalid-feedback">Please enter your contact info.</div>
        </div>
        <div class="mb-3">
          <label for="location" class="form-label">Location</label>
          <input type="text" id="location" class="form-control" name="location" required />
          <div class="invalid-feedback">Please enter your location.</div>
        </div>
        <button type="submit" class="btn btn-success w-100">Submit Donation</button>
      </form>
    </div>
  </main>

  <footer class="footer mt-5 py-3 bg-light text-center">
    <p>&copy; 2025 Meal4All. Making a difference, one meal at a time.</p>
  </footer>

  <script>
    // Bootstrap form validation
    (() => {
      'use strict'
      const forms = document.querySelectorAll('.needs-validation');
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })
    })();
  </script>
</body>
</html>
