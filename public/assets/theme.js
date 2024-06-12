function updatePreview() {
    const themeSelect = document.getElementById('theme');
    const selectedOption = themeSelect.options[themeSelect.selectedIndex];
    const previewUrl = selectedOption.getAttribute('data-preview-url');
    const description = selectedOption.getAttribute('data-description');
    const themePreview = document.getElementById('themePreview');
    const themeDescription = document.getElementById('themeDescription');

    if (previewUrl) {
        themePreview.src = previewUrl;
        themePreview.style.display = 'block';
    } else {
        themePreview.style.display = 'none';
    }

    themeDescription.textContent = description || 'No description available.';
}
window.onload = updatePreview;