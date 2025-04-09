// FAQ deletion confirmation
document.addEventListener('DOMContentLoaded', function() {
    // Find the delete FAQ buttons
    const deleteFaqButtons = document.querySelectorAll('.delete-faq');
    if (deleteFaqButtons) {
        deleteFaqButtons.forEach(button => {
            button.addEventListener('click', function() {
                const faqId = this.getAttribute('data-id');
                const confirmDeleteBtn = document.getElementById('confirmDelete');
                confirmDeleteBtn.href = `${BASE_URL}/index.php?controller=FAQAdmin&action=delete&id=${faqId}`;
            });
        });
    }
    
    // News deletion confirmation
    const deleteNewsButtons = document.querySelectorAll('.delete-news');
    if (deleteNewsButtons) {
        deleteNewsButtons.forEach(button => {
            button.addEventListener('click', function() {
                const newsId = this.getAttribute('data-id');
                const confirmDeleteBtn = document.getElementById('confirmDelete');
                confirmDeleteBtn.href = `${BASE_URL}/index.php?controller=NewsAdmin&action=delete&id=${newsId}`;
            });
        });
    }
});