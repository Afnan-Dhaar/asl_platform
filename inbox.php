<?php
include("config.php");
include("includes/header.php");

$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>
<?php
$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$offset = ($page - 1) * $limit;

$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT $limit OFFSET $offset");

$totalResult = $conn->query("SELECT COUNT(*) as total FROM contact_messages");
$totalRows = $totalResult->fetch_assoc()['total'];

$totalPages = ceil($totalRows / $limit);
?>
<?php
$totalMessages = $conn->query("SELECT COUNT(*) as total FROM contact_messages")->fetch_assoc()['total'];

$unreadMessages = $conn->query("SELECT COUNT(*) as total FROM contact_messages WHERE is_read=0")->fetch_assoc()['total'];

$todayMessages = $conn->query("SELECT COUNT(*) as total FROM contact_messages WHERE DATE(created_at)=CURDATE()")->fetch_assoc()['total'];
?>

<div class="activity-stats">

<div class="stat-card">
<h3>Total Messages</h3>
<p><?php echo $totalMessages; ?></p>
</div>

<div class="stat-card">
<h3>Unread Messages</h3>
<p><?php echo $unreadMessages; ?></p>
</div>

<div class="stat-card">
<h3>Today</h3>
<p><?php echo $todayMessages; ?></p>
</div>

</div>
<div class="container">

<h2 class="inbox-page-text-color">Contact Messages</h2>

<div class="message-search">
<input type="text" id="messageSearch" placeholder="Search messages...">
</div>

<table class="activity-table">

<thead>
<tr>
<th>Name</th>
<th>Email</th>
<th>View</th>
<th>Status</th>
<th>Date</th>
<th>Action</th>
</tr>
</thead>

<tbody id="messagesTable">

<?php while($row = $result->fetch_assoc()){ ?>

<tr>

<td><?php echo htmlspecialchars($row['name']); ?></td>

<td><?php echo htmlspecialchars($row['email']); ?></td>

<td>
<button style="margin: 0px;" class="view-btn" onclick="viewMessage(<?php echo $row['id']; ?>)">
View
</button>
</td>
<td>
<?php if($row['is_read'] == 0){ ?>
    <span class="msg-status-badge unread">Unread</span>
<?php } else { ?>
    <span class="msg-status-badge read">Read</span>
<?php } ?>
</td>
<td><?php echo $row['created_at']; ?></td>

<td>
<button class="delete-btn" onclick="deleteMessage(<?php echo $row['id']; ?>)">
Delete
</button>
</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>
<div class="pagination">

<?php for($i=1;$i<=$totalPages;$i++){ ?>

<a href="?page=<?php echo $i; ?>" 
class="<?php echo ($i==$page)?'active':''; ?>">
<?php echo $i; ?>
</a>

<?php } ?>

</div>
<div id="messageModal" class="message-modal">

<div class="message-modal-content">

<span class="close-modal" onclick="closeInboxModal()">&times;</span>

<h3 id="modalName"></h3>

<p id="modalEmail"></p>

<p id="modalMessage"></p>

<hr>

<h4 class="inbox-page-text-color">Reply</h4>

<textarea id="replyText" placeholder="Type your reply"></textarea>

<button onclick="sendReply()" class="reply-btn">Send Reply</button>

</div>

</div>

<?php include("includes/footer.php"); ?>