<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'ngo') {
    header("Location: login.html?error=unauthorized");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>NGO Dashboard - Meal4All</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      margin: 0;
      padding: 0;
    }
    /* Header */
header {
  background-color: #2e7d32; /* Deep green */
  color: white;
  padding: 20px;
  text-align: center;
}

header h1 {
  margin: 0;
  font-size: 2.5rem;
  color: white;
  font-family: Arial, sans-serif;
  z-index: 2;
  position: relative;
}


nav {
  margin-top: 10px;
}

nav a {
  color: white;
  text-decoration: none;
  margin: 0 15px;
  font-weight: 500;
  transition: color 0.3s;
}

nav a:hover {
  color: #c8e6c9; /* Light green hover */
}
    .container {
      max-width: 1000px;
      margin: 40px auto;
      padding: 20px;
      background: white;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      border-radius: 8px;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #198754;
    }

    .flex-wrapper {
      display: flex;
      gap: 40px;
      flex-wrap: wrap;
      justify-content: space-between;
    }

    .verification-details,
    .verification-form {
      flex: 1 1 45%;
      background: #f0f8f5;
      padding: 25px;
      border-radius: 8px;
      box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
    }

    .verification-details h3,
    .verification-form h3 {
      margin-top: 0;
      margin-bottom: 20px;
      color: #0f5132;
      border-bottom: 2px solid #198754;
      padding-bottom: 8px;
      font-weight: 700;
    }

    .verification-details p {
      margin: 10px 0;
      font-size: 1rem;
      color: #333;
    }

    strong {
      color: #198754;
    }

    label {
      font-weight: 600;
      display: block;
      margin-bottom: 6px;
      color: #0f5132;
    }

    input[type="text"],
    textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1.5px solid #ced4da;
      border-radius: 6px;
      font-size: 1rem;
      resize: vertical;
      transition: border-color 0.3s;
    }
    input[type="text"]:focus,
    textarea:focus {
      border-color: #198754;
      outline: none;
      background: #e9f5ef;
    }

    button {
      background-color: #198754;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 6px;
      font-size: 1.1rem;
      font-weight: 700;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.3s;
    }
    button:hover {
      background-color: #145c32;
    }

   /* Footer */
footer {
  background-color: #e8f5e9; /* Light green background */
  color: #2e7d32;
  text-align: center;
  padding: 15px 0;
  margin-top: 30px;
  font-weight: 500;
}


    @media (max-width: 768px) {
      .flex-wrapper {
        flex-direction: column;
      }
      .verification-details,
      .verification-form {
        flex: 1 1 100%;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>Meal4All</h1>
    <nav>
      <a href="donations.php">Donations</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

  <div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
    <p style="text-align:center; font-size:1.1rem; color:#444;">This is your NGO dashboard.</p>

    <div class="flex-wrapper">
      <section class="verification-details">
        <h3>NGO Verification Details</h3>
        <?php
        require 'db.php';
        $ngo_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("SELECT registration_number, address, contact_person, contact_number, verification_status FROM ngo_verifications WHERE ngo_id=?");
        $stmt->bind_param("i", $ngo_id);
        $stmt->execute();
        $stmt->bind_result($reg_num, $addr, $contact_p, $contact_n, $status);
        if ($stmt->fetch()) {
            echo "<p><strong>Verification Status:</strong> " . htmlspecialchars($status) . "</p>";
            echo "<p><strong>Registration Number:</strong> " . htmlspecialchars($reg_num) . "</p>";
            echo "<p><strong>Address:</strong> " . nl2br(htmlspecialchars($addr)) . "</p>";
            echo "<p><strong>Contact Person:</strong> " . htmlspecialchars($contact_p) . "</p>";
            echo "<p><strong>Contact Number:</strong> " . htmlspecialchars($contact_n) . "</p>";
        } else {
            echo "<p>You have not submitted verification details yet.</p>";
        }
        $stmt->close();
        ?>
      </section>

      <section class="verification-form">
        <h3>Submit / Update Verification</h3>
        <form action="ngo_verification_process.php" method="POST">
          <label for="registration_number">Registration Number:</label>
          <input type="text" id="registration_number" name="registration_number" required>

          <label for="address">Address:</label>
          <textarea id="address" name="address" rows="4" required></textarea>

          <label for="contact_person">Contact Person:</label>
          <input type="text" id="contact_person" name="contact_person" required>

          <label for="contact_number">Contact Number:</label>
          <input type="text" id="contact_number" name="contact_number" required>

          <button type="submit">Submit Verification</button>
        </form>
      </section>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 Meal4All. Making a difference, one meal at a time.</p>
  </footer>
</body>
</html>
