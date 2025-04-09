<?php
// blood-donation-system/app/views/faqs/edit.php

// Ensure FAQ data is passed from the controller
// $faq is already passed from the controller
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit FAQ</title>
    <link rel="stylesheet" href="/blood-donation-system/public/css/styles.css">
</head>
<body>

    <h1>Edit FAQ</h1>

    <form action="/php-blood-donation-system/public/index.php?controller=Faq&action=update&id=<?php echo $faq['id']; ?>" method="POST">
        <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($faq['title']); ?>" required>
        </div>
        <div>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($faq['description']); ?></textarea>
        </div>
        <div>
            <button type="submit">Update FAQ</button>
        </div>
    </form>
    <a href="/php-blood-donation-system/public/index.php?controller=Faq&action=manage">Back to FAQ List</a>

</body>
</html>
