document.getElementById('select-media-button').addEventListener('click', openModal);
document.getElementById('media-selector-modal').addEventListener('click', closeModal);
document.getElementById('media-selector-content').addEventListener('click', (e) => e.stopPropagation());
document.getElementById('submit-selection-button').addEventListener('click', () => {
    updateSelectedMediaContainer();
    closeModal();
});

document.getElementById('post-form').addEventListener('submit', async function(event) {
    event.preventDefault();
    const postText = document.getElementById('post-text').value;
    if (selectedMediaUrls.length > 0 && postText) {
        const attachments = selectedContentIds.map((contentId, index) => ({
            contentId: contentId,
            contentType: 1,
            pos: index
        }));
        const postBody = {
            content: postText,
            fypFlags: 0,
            inReplyTo: null,
            quotedPostId: null,
            attachments: attachments,
            scheduledFor: 0,
            expiresAt: 0,
            postReplyPermissionFlags: [],
            pinned: 0,
            wallIds: []
        };

        try {
            const response = await fetch(postUrl, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(postBody)
            });
            const result = await response.json();
            if (result.success) {
                alert('Post created successfully!');
            } else {
                console.error('Failed to create post:', result);
                alert('Failed to create post.');
            }
        } catch (error) {
            console.error('Error creating post:', error);
            alert('Error creating post.');
        }
    } else {
        alert('Please select media and enter text to create a post.');
    }
});
