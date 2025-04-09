<?php
$content = function () {
?>
<div class="container">
    <h1>Create New User</h1>
    <form action="<?= BASE_URL ?>/index.php?controller=User&action=store" method="POST">
        <div class="form-group">
            <label for="username">CCCD (Username):</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" name="fullName" required>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required>
        </div>
        <div class="form-group">
            <label for="sex">Sex:</label>
            <select id="sex" name="sex" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
        </div>
        <button type="submit">Create User</button>
    </form>
    <a href="<?= BASE_URL ?>/index.php?controller=User&action=index">Back to User List</a>
</div>
<?php
};
include_once '../../layouts/AdminLayout/AdminLayout.php';
?>