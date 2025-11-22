<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../db_connect.php";

// DELETE MEMBER
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM members WHERE id=$id");
    $msg = "Member deleted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Members – Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../style.css">

  <!-- Admin Page Theme Color -->
  <style>
    :root {
      --admin-accent: #6c5ce7; /* purple color for admin pages */
    }
    .admin-title {
      color: var(--admin-accent);
      font-size: 30px;
      font-weight: 700;
      margin-bottom: 10px;
      text-align:center;
    }
    .admin-badge {
      background: var(--admin-accent);
      color: #fff;
      padding: 8px 14px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 600;
    }
  </style>

</head>

<body>

<!-- HEADER -->
<header class="site-header">
  <div class="container header-inner">
    <h1 class="logo">Admin Panel</h1>
    <nav class="main-nav">
      <a href="dashboard.php">Dashboard</a>
      <a href="../logout.php">Logout</a>
    </nav>
  </div>
</header>

<main class="container">

  <!-- MAIN CARD BOX (same as dashboard style) -->
  <section class="card" style="padding:35px;">

    <h2 class="admin-title">👥 Manage Members</h2>

    <?php if(!empty($msg)): ?>
      <div class="card" 
           style="background:#e8ffe8; color:#127c12; padding:12px; margin-bottom:15px; border-radius:8px;">
        <?= $msg ?>
      </div>
    <?php endif; ?>

    <!-- MEMBERS TABLE -->
    <table class="table">
      <thead>
        <tr>
          <th>Enrollment No</th>
          <th>Name</th>
          <th>Email</th>
          <th>Joined On</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
        <?php
          $members = $conn->query("SELECT * FROM members ORDER BY id DESC");
          while($m = $members->fetch_assoc()):
        ?>
        <tr>
          <td><?= htmlspecialchars($m['enrollment_no']) ?></td>
          <td><?= htmlspecialchars($m['name']) ?></td>
          <td><?= htmlspecialchars($m['email']) ?></td>
          <td><?= htmlspecialchars($m['created_at']) ?></td>

          <td>
            <!-- Edit -->
            <a class="admin-badge"
               href="edit_member.php?id=<?= $m['id'] ?>"
               style="padding:6px 10px; font-size:14px;">
               Edit
            </a>

            <!-- Delete -->
            <a class="admin-badge"
               href="manage_members.php?delete=<?= $m['id'] ?>"
               onclick="return confirm('Are you sure you want to delete this member?');"
               style="background:#d63031; padding:6px 10px; font-size:14px;">
               Delete
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>

    </table>

  </section>

</main>

<!-- FOOTER -->
<footer class="site-footer">
  <div class="container">
    <p>© <script>document.write(new Date().getFullYear())</script> My Library Admin Panel</p>
  </div>
</footer>

</body>
</html>
