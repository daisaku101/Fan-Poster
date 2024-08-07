document.addEventListener('DOMContentLoaded', () => {
    const postForm = document.getElementById('post-form');

    // Remove any existing event listeners to prevent duplicates
    postForm.removeEventListener('submit', handleSubmit);
    postForm.addEventListener('submit', handleSubmit);
});

async function handleSubmit(e) {
    e.preventDefault(); // Prevent default form submission
    const postText = document.getElementById('post-text').value;
    const selectedContentIds = getSelectedMediaIds(); // Function to get selected media IDs
    const price = parseFloat(document.getElementById('price').value) || 0;

    if (!Array.isArray(selectedContentIds) || selectedContentIds.length === 0) {
        console.error('No media selected');
        return;
    }

    const attachments = selectedContentIds.map((contentId, index) => ({
        contentId,
        contentType: 1,
        pos: index,
        price: selectedMediaPrices[index] // Add price to attachments
    }));

    const postContent = {
        content: postText,
        fypFlags: 0,
        inReplyTo: null,
        quotedPostId: null,
        attachments,
        scheduledFor: 0,
        expiresAt: 0,
        postReplyPermissionFlags: [],
        pinned: 0,
        wallIds: [],
        price // Include the overall bundle price
    };

    try {
        const response = await fetch('create-post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(postContent)
        });
        const data = await response.json();
        if (data.error) {
            console.error('Error creating post:', data.error);
        } else {
            console.log('Post created:', data);
        }
    } catch (error) {
        console.error('Error creating post:', error);
    }
}

function openModal() {
    document.getElementById('media-selector-modal').style.display = 'flex';
    fetchMedia(); // Fetch media when the modal is opened
}

function closeModal() {
    document.getElementById('media-selector-modal').style.display = 'none';
}

function getSelectedMediaIds() {
    const selectedMediaContainer = document.getElementById('selected-media-container');
    const selectedMedia = selectedMediaContainer.querySelectorAll('.selected-media');
    return Array.from(selectedMedia).map(media => media.dataset.mediaId);
}

async function storeToken(authToken) {
    try {
        const response = await fetch('store-token.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ authToken })
        });
        const data = await response.json();
        console.log('Token stored:', data);
    } catch (error) {
        console.error('Error storing token:', error);
    }
}

function handleLogin() {
    const authToken = document.getElementById('auth-token').value;
    storeToken(authToken);
}
