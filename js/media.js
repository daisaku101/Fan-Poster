let selectedMediaUrls = [];
let selectedContentIds = [];

async function fetchMedia() {
    console.log("Fetching new media:", document.getElementById('media-status'));
    document.getElementById('media-status').innerText = 'Fetching media...'; // Display loading message
    try {
        const fetchMedia = await fetch('media-fetch.php');
        const response = await fetch('load-media.php');
        const data = await response.json();
        if (Array.isArray(data.media)) {
            document.getElementById('media-status').innerText = ''; // Clear status message on success
            renderMedia(data.media, 'media-selector');
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
    mediaArray.forEach(file => {
        const filePath = `media/${file}`;
        let mediaItem = createMediaElement(filePath, file);
        if (mediaItem) {
            mediaItem.onclick = (e) => {
                e.stopPropagation();
                selectMedia(filePath, file, mediaItem);
            };
            container.appendChild(mediaItem);
        }
    });
    updateSelectedMediaPreview();
}

function createMediaElement(filePath, file) {
    let mediaItem;
    if (filePath.endsWith('.jpg') || filePath.endsWith('.jpeg') || filePath.endsWith('.png')) {
        mediaItem = document.createElement('img');
        mediaItem.src = filePath;
        mediaItem.alt = file;
    } else if (filePath.endsWith('.mp4')) {
        mediaItem = document.createElement('video');
        mediaItem.src = filePath;
        mediaItem.controls = true;
    }
    if (mediaItem) {
        mediaItem.classList.add('media-item');
        mediaItem.style.maxWidth = '200px';
        mediaItem.style.maxHeight = '200px';
    }
    return mediaItem;
}

function selectMedia(filePath, contentId, element) {
    const index = selectedMediaUrls.indexOf(filePath);
    if (index > -1) {
        selectedMediaUrls.splice(index, 1);
        selectedContentIds.splice(index, 1);
        element.classList.remove('selected');
    } else {
        selectedMediaUrls.push(filePath);
        selectedContentIds.push(contentId);
        element.classList.add('selected');
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
            const removeBtn = document.createElement('button');
            removeBtn.classList.add('remove-btn');
            removeBtn.innerText = 'Remove';
            removeBtn.onclick = () => {
                selectedMediaUrls.splice(index, 1);
                selectedContentIds.splice(index, 1);
                updateSelectedMediaContainer();
                updateSelectedMediaPreview();
            };
            mediaPreview.appendChild(removeBtn);
            container.appendChild(mediaPreview);
        }
    });
}

function updateSelectedMediaPreview() {
    const container = document.getElementById('selected-media-preview');
    container.innerHTML = '';
    selectedMediaUrls.forEach(filePath => {
        const mediaPreview = createMediaElement(filePath);
        if (mediaPreview) {
            container.appendChild(mediaPreview);
        }
    });
}
