let selectedMediaUrls = [];
let selectedContentIds = [];
let selectedMediaPrices = [];

async function fetchMedia() {
    console.log("Fetching new media:", document.getElementById('media-status'));
    document.getElementById('media-status').innerText = 'Fetching media...'; // Display loading message
    try {
        const response = await fetch('load-media.php');
        
        const data = await response.json();
        if (Array.isArray(data.media)) {
            document.getElementById('media-status').innerText = ''; // Clear status message on success
            renderMedia(data.media, 'media-selector');
            const fetchMedia = await fetch('media-fetch.php');
        } else {
            document.getElementById('media-status').innerText = 'Failed to fetch media.';
            console.error('Failed to fetch media:', data);
        }
    } catch (error) {
        document.getElementById('media-status').innerText = 'Error fetching media.';
        console.error('Error fetching media:', error);
    }
}

function renderMedia(mediaArray, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = ''; // Clear existing content
    console.log("Media:", mediaArray);
    
    mediaArray.forEach(media => {
        const filePath = media.path;
        const mediaId = media.fansly_media_id;
        let mediaItem = createMediaElement(filePath, media.filename, mediaId);
        if (mediaItem) {
            mediaItem.onclick = (e) => {
                e.stopPropagation();
                selectMedia(filePath, mediaId, mediaItem);
            };
            container.appendChild(mediaItem);
        }
    });
    updateSelectedMediaPreview();
}

function createMediaElement(filePath, file, mediaId) {
    let mediaItem = document.createElement('div');
    mediaItem.classList.add('media-item');
    mediaItem.style.maxWidth = '200px';
    mediaItem.style.maxHeight = '200px';
    mediaItem.dataset.mediaId = mediaId; // Add the mediaId as a data attribute

    let mediaContent;
    if (filePath.endsWith('.jpg') || filePath.endsWith('.jpeg') || filePath.endsWith('.png')) {
        mediaContent = document.createElement('img');
        mediaContent.src = filePath;
        mediaContent.alt = file;
    } else if (filePath.endsWith('.mp4')) {
        mediaContent = document.createElement('video');
        mediaContent.src = filePath;
        mediaContent.controls = true;
    }
    if (mediaContent) {
        mediaItem.appendChild(mediaContent);
    }

    return mediaItem;
}

function selectMedia(filePath, contentId, element) {
    const index = selectedMediaUrls.indexOf(filePath);
    if (index > -1) {
        selectedMediaUrls.splice(index, 1);
        selectedContentIds.splice(index, 1);
        selectedMediaPrices.splice(index, 1); // Remove the corresponding price
        element.classList.remove('selected');
        element.classList.remove('selected-media'); // Remove the 'selected-media' class
    } else {
        selectedMediaUrls.push(filePath);
        selectedContentIds.push(contentId);
        const price = prompt("Enter the price for the media item:"); // Prompt user for price
        selectedMediaPrices.push(price ? parseFloat(price) : 0); // Store the price, default to 0
        element.classList.add('selected');
        element.classList.add('selected-media'); // Add the 'selected-media' class
    }
    updateSelectedMediaContainer();
    updateSelectedMediaPreview();
}

function updateSelectedMediaContainer() {
    const container = document.getElementById('selected-media-container');
    container.innerHTML = '';
    selectedMediaUrls.forEach((filePath, index) => {
        const mediaPreview = createMediaElement(filePath, index);
        if (mediaPreview) {
            mediaPreview.dataset.mediaId = selectedContentIds[index]; // Ensure mediaId is preserved
            mediaPreview.classList.add('selected-media'); // Add the 'selected-media' class

            // Add price display and edit input
            const priceInput = document.createElement('input');
            priceInput.type = 'number';
            priceInput.value = selectedMediaPrices[index];
            priceInput.classList.add('price-input');
            priceInput.onchange = (e) => {
                selectedMediaPrices[index] = parseFloat(e.target.value);
                updateSelectedMediaPreview(); // Update preview when price changes
            };

            const priceLabel = document.createElement('label');
            priceLabel.innerText = 'Price: ';
            priceLabel.appendChild(priceInput);

            const priceContainer = document.createElement('div');
            priceContainer.classList.add('price-container');
            priceContainer.appendChild(priceLabel);

            const removeBtn = document.createElement('button');
            removeBtn.classList.add('remove-btn');
            removeBtn.innerText = 'Remove';
            removeBtn.onclick = () => {
                selectedMediaUrls.splice(index, 1);
                selectedContentIds.splice(index, 1);
                selectedMediaPrices.splice(index, 1); // Remove the corresponding price
                updateSelectedMediaContainer();
                updateSelectedMediaPreview();
            };

            const wrapper = document.createElement('div');
            wrapper.classList.add('media-item-wrapper');
            wrapper.appendChild(mediaPreview);
            wrapper.appendChild(priceContainer);
            wrapper.appendChild(removeBtn);

            container.appendChild(wrapper);
        }
    });
}


function updateSelectedMediaPreview() {
    const container = document.getElementById('selected-media-preview');
    container.innerHTML = '';
    selectedMediaUrls.forEach((filePath, index) => {
        const mediaPreview = createMediaElement(filePath, null, selectedContentIds[index]);
        if (mediaPreview) {
            mediaPreview.dataset.mediaId = selectedContentIds[index]; // Ensure mediaId is preserved
            
            // Add price display to the preview
            const priceDisplay = document.createElement('div');
            priceDisplay.innerText = 'Price: $' + selectedMediaPrices[index];
            priceDisplay.classList.add('price-display');
            
            mediaPreview.appendChild(priceDisplay);
            container.appendChild(mediaPreview);
        }
    });
}

// Implement the getSelectedMediaIds function
function getSelectedMediaIds() {
    const selectedMediaContainer = document.getElementById('selected-media-container');
    const selectedMedia = selectedMediaContainer.querySelectorAll('.selected-media');
    return Array.from(selectedMedia).map(media => media.dataset.mediaId);
}
