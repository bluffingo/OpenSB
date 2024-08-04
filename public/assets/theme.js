function updatePreview() {
    const themeSelect = document.getElementById('theme');
    const selectedOption = themeSelect.options[themeSelect.selectedIndex];
    const previewUrl = selectedOption.getAttribute('data-preview-url');
    const description = selectedOption.getAttribute('data-description');
    const author = selectedOption.getAttribute('data-author');

    const themePreview = document.getElementById('themePreview');
    const themeDescription = document.getElementById('themeDescription');
    const themeAuthor = document.getElementById('themeAuthor');

    if (previewUrl) {
        themePreview.src = previewUrl;
        themePreview.style.display = 'block';
    } else {
        themePreview.style.display = 'none';
    }

    themeDescription.textContent = description || 'No description available.';
    themeAuthor.textContent = author ? `By ${author}` : '';
}
window.onload = updatePreview;