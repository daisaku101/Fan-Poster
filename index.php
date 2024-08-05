<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fansly Post Creator</title>
    <link rel="stylesheet" href="css/styles.css">
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
                    <button type="button" id="select-media-button">Select Media</button>
                </div>
                <div id="selected-media-container" class="media-container"></div>
                <div class="form-group">
                    <button type="submit">Create Post</button>
                </div>
            </form>
        </div>
    </div>

    <div id="media-selector-modal">
        <div id="media-selector-content">
            <h2>Select Media</h2>
            <div id="media-selector" class="media-container"></div>
            <button id="submit-selection-button">Submit Selection</button>
        </div>
    </div>

    <script src="js/media.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
