const url = 'https://cors-anywhere.herokuapp.com/https://apiv3.fansly.com/api/v1/media/vaultnew?albumId=654235541616730112&mediaType=&search=&before=0&after=0&ngsw-bypass=true';
const postUrl = 'https://cors-anywhere.herokuapp.com/https://apiv3.fansly.com/api/v1/post?ngsw-bypass=true';
const headers = {
    'Authorization': 'Njc2NDI4NTE0NjA1NDEyMzUzOjE6Mjo1NTliMDY0ZWU1OGQ3ZWM1YjU0OTEwZWQ5NDFhNzM',
    'Content-Type': 'application/json'
};
let selectedMediaUrls = [];
let selectedContentIds = [];

async function fetchMedia() {
    try {
        const response = await fetch('media-fetch.php');
        const data = await response.json();
        if (data.success) {
            renderMedia(data.response.media, 'media-selector');
        } else {
            console.error('Failed to fetch media:', data.error);
        }
    } catch (error) {
        console.error('Error fetching media:', error);
    }
}

async function fetchMediaAsBlob(mediaUrl) {
    try {
        const response = await fetch(`https://cors-anywhere.herokuapp.com/${mediaUrl}`);
        const blob = await response.blob();
        const blobUrl = URL.createObjectURL(blob);
        return blobUrl;
    } catch (error) {
        console.error('Error fetching media:', error);
    }
}

async function renderMedia(mediaArray, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = ''; // Clear existing content
    for (const media of mediaArray) {
        const blobUrl = await fetchMediaAsBlob(media.locations[0].location);
        if (media.mimetype === 'image/jpeg') {
            const img = document.createElement('img');
            img.src = blobUrl;
            img.alt = media.filename;
            img.onclick = (e) => {
                e.stopPropagation();
                selectMedia(blobUrl, media.contentId, img);
            };
            container.appendChild(img);
        } else if (media.mimetype === 'video/mp4') {
            const video = document.createElement('video');
            video.src = blobUrl;
            video.controls = false;
            video.onclick = (e) => {
                e.stopPropagation();
                selectMedia(blobUrl, media.contentId, video);
            };
            container.appendChild(video);
        }
    }
}

function selectMedia(blobUrl, contentId, element) {
    const index = selectedMediaUrls.indexOf(blobUrl);
    if (index > -1) {
        selectedMediaUrls.splice(index, 1);
        selectedContentIds.splice(index, 1);
        element.classList.remove('selected');
    } else {
        selectedMediaUrls.push(blobUrl);
        selectedContentIds.push(contentId);
        element.classList.add('selected');
    }
}

function openModal() {
    document.getElementById('media-selector-modal').style.display = 'flex';
    fetchMedia(); // Fetch media when the modal is opened
}

function closeModal() {
    document.getElementById('media-selector-modal').style.display = 'none';
}

function updateSelectedMediaContainer() {
    const container = document.getElementById('selected-media-container');
    container.innerHTML = '';
    selectedMediaUrls.forEach((blobUrl, index) => {
        const mediaPreview = document.createElement('div');
        mediaPreview.classList.add('media-preview');
        if (blobUrl.endsWith('.jpg') || blobUrl.endsWith('.jpeg')) {
            const img = document.createElement('img');
            img.src = blobUrl;
            mediaPreview.appendChild(img);
        } else if (blobUrl.endsWith('.mp4')) {
            const video = document.createElement('video');
            video.src = blobUrl;
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
        };
        mediaPreview.appendChild(removeBtn);
        container.appendChild(mediaPreview);
    });
}
