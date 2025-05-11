<?php
include("database.php");
if (isset($_GET['resolve_id'])) {
  $id = intval($_GET['resolve_id']);
  $conn->query("UPDATE user_concerns SET status='completed' WHERE id=$id");
}
$result = $conn->query("SELECT * FROM user_concerns ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Concerns Dashboard</title>
  <link rel="icon" href="./css/images/favicon.ico" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background: #f7f9fc;
      margin: 0;
      padding: 40px;
      color: #333;
    }
    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 30px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    th, td {
      padding: 15px 20px;
      text-align: left;
    }
    th {
      background: #f39c12;
      color: white;
      font-size: 1rem;
    }
    tr:nth-child(even) {
      background: #fdfdfd;
    }
    tr:hover {
      background: #fef3e0;
    }
    td {
      vertical-align: top;
    }
    td.message-cell {
      max-width: 250px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      position: relative;
    }
    td.message-cell:hover {
      white-space: normal;
      background-color: #fff9e7;
      z-index: 1;
    }
    a {
      text-decoration: none;
      color: #3498db;
      font-weight: 500;
    }
    a:hover {
      color: #2980b9;
      text-decoration: underline;
    }
    .closed {
      color: green;
      font-weight: bold;
    }
  </style>
</head>
<body>

<h2>User Concerns Dashboard</h2>
<table>
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Subject</th>
    <th>Message</th>
    <th>Status</th>
    <th>Action</th>
  </tr>
  <?php while($row = $result->fetch_assoc()) { ?>
    <tr>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['email']) ?></td>
      <td><?= htmlspecialchars($row['subject']) ?></td>
      <td class="message-cell" title="<?= htmlspecialchars($row['message']) ?>">
        <?= htmlspecialchars($row['message']) ?>
      </td>
      <td><?= $row['status'] === 'completed' ? '<span class="closed">Closed</span>' : 'Pending' ?></td>
      <td>
        <?php if ($row['status'] === 'pending') { ?>
          <a href="?resolve_id=<?= $row['id'] ?>" onclick="return confirm('Mark this as completed?')">Mark as Completed</a>
        <?php } else { ?>
          <span class="closed">✔</span>
        <?php } ?>
      </td>
    </tr>
  <?php } ?>
</table>

</body>
</html>
