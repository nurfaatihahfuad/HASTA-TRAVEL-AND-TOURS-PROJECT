<!DOCTYPE html>
<html>
<head>
  <style>
    .status {
      font-weight: bold;
      padding: 4px 8px;
      border-radius: 4px;
    }
    .pending { background: #fff3cd; color: #856404; }
    .approved { background: #d4edda; color: #155724; }
    .rejected { background: #f8d7da; color: #721c24; }
    .btn { padding: 4px 8px; margin: 2px; cursor: pointer; border: none; }
    .btn-success { background-color: #28a745; color: white; }
    .btn-danger { background-color: #dc3545; color: white; }
  </style>
</head>
<body>

<h2>Payment Verification Table</h2>

<table border="1" cellpadding="8">
  <thead>
    <tr>
      <th>Payment ID</th>
      <th>Booking ID</th>
      <th>User ID</th>
      <th>Type</th>
      <th>Status</th>
      <th>Verified By</th>
      <th>Verified Date</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($payments as $payment): ?>
    <tr>
      <td><?= $payment['payment_id'] ?></td>
      <td><?= $payment['booking_id'] ?></td>
      <td><?= $payment['user_id'] ?></td>
      <td><?= $payment['type'] ?></td>
      <td><span class="status <?= strtolower($payment['status']) ?>"><?= $payment['status'] ?></span></td>
      <td><?= $payment['verified_by'] ?? '-' ?></td>
      <td><?= $payment['verified_date'] ?? '-' ?></td>
      <td>
        <form method="POST">
          <input type="hidden" name="payment_id" value="<?= $payment['payment_id'] ?>">
          <button name="status" value="Approved" class="btn btn-success">Approve</button>
          <button name="status" value="Rejected" class="btn btn-danger">Reject</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentID = $_POST['payment_id'];
    $status = $_POST['status'];
    echo "<p><strong>" . verifyPayment($payments, $paymentID, $status) . "</strong></p>";
}
?>

</body>
</html>
