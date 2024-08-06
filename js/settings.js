document.addEventListener('DOMContentLoaded', function() {
    const authTokenInput = document.getElementById('auth-token');
    const saveButton = document.getElementById('save-btn');
    const statusMsg = document.getElementById('status-msg');
    let initialToken = authTokenInput.value; // Store the initial value

    // Function to update the button's disabled status
    function updateButtonState() {
        saveButton.disabled = (authTokenInput.value === initialToken);
    }

    // Initially disable the button if the input value matches the initial value
    updateButtonState();

    // Event listener for input changes
    authTokenInput.addEventListener('input', updateButtonState);

    // Function to handle the save settings action
    function handleSaveSettings() {
        const newToken = authTokenInput.value;
        if (newToken === initialToken) {
            statusMsg.textContent = "No changes to save.";
            statusMsg.style.color = 'gray';
            return;
        }
        saveButton.disabled = true; // Disable the button during the request
        fetch('saveSettings.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `authToken=${encodeURIComponent(newToken)}`
        })
        .then(response => response.json())
        .then(data => {
            statusMsg.textContent = data.message; // Display message on the page
            statusMsg.style.color = data.status === 'success' ? 'green' : 'red'; // Change text color based on success or error
            if (data.status === 'success') {
                initialToken = newToken; // Update the initial token after successful save
            }
            updateButtonState(); // Re-check the button state post-update
        })
        .catch(error => {
            console.error('Failed to save settings:', error);
            statusMsg.textContent = 'Failed to save settings.';
            statusMsg.style.color = 'red'; // Red color for errors
            saveButton.disabled = false; // Re-enable the button if there's an error
        });
    }

    // Attach the event handler for the Save Settings button
    saveButton.addEventListener('click', handleSaveSettings);
});
