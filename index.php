<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fansly Post Creator</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/media.css">
</head>
<body>
    <header>
        <h1>Fansly Post Creator</h1>
    </header>
    <div class="container">
        <div class="form-container">
            <form id="post-form">
                <div class="form-group">
                    <label for="post-text">Post Text</label>
                    <textarea id="post-text" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <button type="button" id="select-media-button" class="btn btn-primary" onclick="openModal()">Select Media</button>
                </div>
                <div id="selected-media-container" class="media-container"></div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Create Post</button>
                </div>
            </form>
        </div>
    </div>

    <div id="media-selector-modal" class="modal">
        <div id="media-selector-content" class="modal-content">
            <span class="close-button" onclick="closeModal()">&times;</span>
            <div id="selected-media-preview" class="media-container"></div>
            <button id="submit-selection-button" class="btn btn-primary" onclick="closeModal()">Submit Selection</button>
            <h2>Select Media</h2>
            <div id="media-selector" class="media-container"></div>
        </div>
    </div>

    <script src="js/main.js"></script>
    <script src="js/media.js"></script>
</body>
</html>
