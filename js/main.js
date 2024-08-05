document.getElementById('post-form').addEventListener('submit', (e) => {
    e.preventDefault();
    // Handle post creation
    console.log('Post created:', document.getElementById('post-text').value);
});

function openModal() {
    document.getElementById('media-selector-modal').style.display = 'flex';
    fetchMedia(); // Fetch media when the modal is opened
}

function closeModal() {
    document.getElementById('media-selector-modal').style.display = 'none';
}
