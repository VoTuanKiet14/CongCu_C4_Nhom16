<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Blood Donation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>

<body>
    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header">
                <h3>Edit User</h3>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/index.php?controller=User&action=update&id=<?= htmlspecialchars($user['cccd']) ?>" method="POST">
                    <input type="hidden" name="username" value="<?= htmlspecialchars($user['cccd']) ?>">

                    <div class="mb-3">
                        <label class="form-label">CCCD:</label>
                        <p class="form-control-static"><?= htmlspecialchars($user['cccd']) ?></p>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone:</label>
                        <input type="tel" class="form-control" id="phone" name="phone"
                            value="<?= htmlspecialchars($user['phone']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role:</label>
                        <select class="form-select" id="role_id" name="role_id" required>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>"
                                    <?= $role['id'] == $user['role_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($role['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="<?= BASE_URL ?>/index.php?controller=User&action=list"
                            class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>