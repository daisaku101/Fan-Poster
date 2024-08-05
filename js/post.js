// Add event listener for form submission
document.getElementById('post-form').addEventListener('submit', async function(event) {
    event.preventDefault(); // Prevent the default form submission

    const postText = document.getElementById('post-text').value; // Get the post text
    if (selectedMediaUrls.length > 0 && postText) {
        // Create an array of attachments from selected media
        const attachments = selectedContentIds.map((contentId, index) => ({
            contentId: contentId,
            contentType: 1,
            pos: index
        }));

        // Create the post body object
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
            // Send the post data to the server
            const response = await fetch('post-submit.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(postBody)
            });

            const result = await response.json(); // Parse the JSON response
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
