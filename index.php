<?php include 'header.php'; ?>
<div class="container">
    <h2>Posting</h2>
    <div class="form-container">
        <form id="post-form">
            <div class="form-group">
                <label for="post-text">Post Text</label>
                <textarea id="post-text" rows="4"></textarea>
            </div>
            <div class="form-group">
                <button type="button" class="btn button-select-media" onclick="openModal()">Select Media</button>
            </div>
            <div id="selected-media-container" class="media-container"></div>
            <!-- New form-group for overall price -->
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" step="0.01" class="form-control">
            </div>
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
        <!-- New Element for Status Message -->
        <div id="media-status" class="media-status"></div>
    </div>
</div>
<script src="js/main.js"></script>
<script src="js/media.js"></script>
<?php include 'footer.php'; ?>
