document.getElementById('post-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const postText = document.getElementById('post-text').value;
    const postContent = {
        content: postText,
        fypFlags: 0,
        inReplyTo: null,
        quotedPostId: null,
        attachments: selectedContentIds.map((contentId, pos) => ({ contentId, contentType: 1, pos })),
        scheduledFor: 0,
        expiresAt: 0,
        postReplyPermissionFlags: [],
        pinned: 0,
        wallIds: []
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
});

function openModal() {
    document.getElementById('media-selector-modal').style.display = 'flex';
    fetchMedia(); // Fetch media when the modal is opened
}

function closeModal() {
    document.getElementById('media-selector-modal').style.display = 'none';
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
