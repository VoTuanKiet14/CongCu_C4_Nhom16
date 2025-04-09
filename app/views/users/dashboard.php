<?php
$content = function () {
    global $user;
?>
<div class="container user-dashboard">
    <div class="dashboard-header">
        <h1>Welcome, <?= htmlspecialchars($user->name) ?></h1>
        <p class="last-login">Last login: <?= date('F j, Y, g:i a', strtotime($user->last_login ?? 'now')) ?></p>
    </div>

    <div class="dashboard-stats">
        <div class="stat-card">
            <h3>My Donations</h3>
            <div class="stat-number"><?= $user->donations_count ?? 0 ?></div>
            <a href="<?= BASE_URL ?>/index.php?controller=BloodDonationHistory&action=userHistory">View History</a>
        </div>
        <div class="stat-card">
            <h3>Upcoming Appointments</h3>
            <div class="stat-number"><?= $user->appointments_count ?? 0 ?></div>
            <a href="<?= BASE_URL ?>/index.php?controller=Appointment&action=userAppointments">Manage Appointments</a>
        </div>
        <div class="stat-card">
            <h3>Blood Type</h3>
            <div class="stat-blood-type"><?= htmlspecialchars($user->blood_type ?? 'Not set') ?></div>
            <a href="<?= BASE_URL ?>/index.php?controller=User&action=editProfile">Update Profile</a>
        </div>
    </div>

    <div class="dashboard-actions">
        <h2>Quick Actions</h2>
        <div class="action-buttons">
            <a href="<?= BASE_URL ?>/index.php?controller=Appointment&action=schedule" class="btn-primary">Schedule Donation</a>
            <a href="<?= BASE_URL ?>/index.php?controller=Event&action=list" class="btn-secondary">Upcoming Events</a>
            <a href="<?= BASE_URL ?>/index.php?controller=News&action=list" class="btn-secondary">Latest News</a>
            <a href="<?= BASE_URL ?>/index.php?controller=User&action=editProfile" class="btn-secondary">Edit Profile</a>
        </div>
    </div>

    <div class="dashboard-sections">
        <div class="section">
            <h2>Recent News</h2>
            <div id="latest-news-feed">
                <!-- Will be populated via AJAX -->
                <p>Loading latest news...</p>
            </div>
        </div>
        <div class="section">
            <h2>Upcoming Events</h2>
            <div id="upcoming-events">
                <!-- Will be populated via AJAX -->
                <p>Loading upcoming events...</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load latest news
    fetch('<?= BASE_URL ?>/index.php?controller=News&action=getLatestJson')
        .then(response => response.json())
        .then(data => {
            const newsContainer = document.getElementById('latest-news-feed');
            newsContainer.innerHTML = '';
            
            if (data.length === 0) {
                newsContainer.innerHTML = '<p>No news available at the moment.</p>';
                return;
            }
            
            data.slice(0, 3).forEach(news => {
                const newsItem = document.createElement('div');
                newsItem.className = 'news-item';
                newsItem.innerHTML = `
                    <h3>${news.title}</h3>
                    <p class="date">${new Date(news.created_at).toLocaleDateString()}</p>
                    <p>${news.summary}</p>
                    <a href="<?= BASE_URL ?>/index.php?controller=News&action=view&id=${news.id}">Read more</a>
                `;
                newsContainer.appendChild(newsItem);
            });
        })
        .catch(error => {
            document.getElementById('latest-news-feed').innerHTML = '<p>Failed to load news.</p>';
        });
    
    // Load upcoming events
    fetch('<?= BASE_URL ?>/index.php?controller=Event&action=getUpcomingJson')
        .then(response => response.json())
        .then(data => {
            const eventsContainer = document.getElementById('upcoming-events');
            eventsContainer.innerHTML = '';
            
            if (data.length === 0) {
                eventsContainer.innerHTML = '<p>No upcoming events at this time.</p>';
                return;
            }
            
            data.slice(0, 3).forEach(event => {
                const eventItem = document.createElement('div');
                eventItem.className = 'event-item';
                eventItem.innerHTML = `
                    <h3>${event.title}</h3>
                    <p class="date">${new Date(event.event_date).toLocaleDateString()}</p>
                    <p>${event.location}</p>
                    <a href="<?= BASE_URL ?>/index.php?controller=Event&action=view&id=${event.id}">View details</a>
                `;
                eventsContainer.appendChild(eventItem);
            });
        })
        .catch(error => {
            document.getElementById('upcoming-events').innerHTML = '<p>Failed to load events.</p>';
        });
});
</script>
<?php
};
include_once __DIR__ . '/../layouts/MainLayout.php';
?>