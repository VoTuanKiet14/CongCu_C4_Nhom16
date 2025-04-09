<?php
$content = function () use ($user) {
?>
<div class="container">
    <h2>Edit User</h2>
    <form action="/users/update/<?php echo $user->id; ?>" method="POST">
        <div class="form-group">
            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($user->fullName); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user->email); ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user->phone); ?>" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="admin" <?php echo $user->role === 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo $user->role === 'user' ? 'selected' : ''; ?>>User</option>
            </select>
        </div>
        <button type="submit">Update User</button>
    </form>
    <a href="/users/index">Back to User List</a>
</div>
<?php
};
include_once '../../layouts/AdminLayout/AdminLayout.php';
?>