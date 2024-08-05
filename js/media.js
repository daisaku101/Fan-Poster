let selectedMediaUrls = [];
let selectedContentIds = [];

async function fetchMedia() {
    try {
        const response = await fetch('load-media.php');
        const data = await response.json();
        if (Array.isArray(data.media)) {
            renderMedia(data.media, 'media-selector');
        } else {
            console.error('Failed to fetch media:', data);
        }
    } catch (error) {
        console.error('Error fetching media:', error);
    }
}

function renderMedia(mediaArray, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = ''; // Clear existing content
    mediaArray.forEach(file => {
        const filePath = `media/${file}`;
        let mediaItem;

        if (filePath.endsWith('.jpg') || filePath.endsWith('.jpeg')) {
            mediaItem = document.createElement('img');
            mediaItem.src = filePath;
            mediaItem.alt = file;
            mediaItem.classList.add('media-item');
            mediaItem.style.maxWidth = '200px'; // Standardize size
            mediaItem.style.maxHeight = '200px';
        } else if (filePath.endsWith('.mp4')) {
            mediaItem = document.createElement('video');
            mediaItem.src = filePath;
            mediaItem.controls = true;
            mediaItem.classList.add('media-item');
            mediaItem.style.maxWidth = '200px'; // Standardize size
            mediaItem.style.maxHeight = '200px';
        }

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
        const mediaPreview = document.createElement('div');
        mediaPreview.classList.add('media-preview');
        if (filePath.endsWith('.jpg') || filePath.endsWith('.jpeg')) {
            const img = document.createElement('img');
            img.src = filePath;
            mediaPreview.appendChild(img);
        } else if (filePath.endsWith('.mp4')) {
            const video = document.createElement('video');
            video.src = filePath;
            video.controls = true;
            mediaPreview.appendChild(video);
        }
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
    });
}

function updateSelectedMediaPreview() {
    const container = document.getElementById('selected-media-preview');
    container.innerHTML = '';
    selectedMediaUrls.forEach((filePath, index) => {
        const mediaPreview = document.createElement('div');
        mediaPreview.classList.add('media-preview');
        if (filePath.endsWith('.jpg') || filePath.endsWith('.jpeg')) {
            const img = document.createElement('img');
            img.src = filePath;
            img.style.maxWidth = '100px'; // Standardize size
            img.style.maxHeight = '100px';
            mediaPreview.appendChild(img);
        } else if (filePath.endsWith('.mp4')) {
            const video = document.createElement('video');
            video.src = filePath;
            video.controls = true;
            video.style.maxWidth = '100px'; // Standardize size
            video.style.maxHeight = '100px';
            mediaPreview.appendChild(video);
        }
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
    });
}
