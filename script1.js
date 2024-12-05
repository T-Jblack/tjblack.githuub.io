document.getElementById('report-form').addEventListener('submit', function(e) {
    const category = document.getElementById('category').value.trim();
    if (category === '') {
        e.preventDefault();
        alert('Category is required.');
    }
});