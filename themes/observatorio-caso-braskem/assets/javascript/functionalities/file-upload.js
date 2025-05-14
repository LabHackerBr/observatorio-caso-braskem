document.addEventListener('DOMContentLoaded', function() {
    let allFiles = [];
    const fileInput = document.getElementById('custom-file-upload');
    const fileList = document.querySelector('.uploaded-files-list');

    if (!fileInput || !fileList) return;

    function updateFileList() {
        fileList.innerHTML = '';

        if (allFiles.length > 0) {
            allFiles.forEach((file, index) => {
                const listItem = document.createElement('li');
                listItem.className = 'file-item';
                listItem.setAttribute('data-file-id', file.name + file.size + file.lastModified);

                const fileName = document.createElement('span');
                fileName.textContent = file.name;

                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = "<img src='/wp-content/themes/observatorio-caso-braskem/assets/images/exclude-file-icon.svg' alt='Remover arquivo' />";
                removeBtn.className = 'remove-file';
                removeBtn.setAttribute('aria-label', 'Remover arquivo');
                removeBtn.setAttribute('type', 'button');
                removeBtn.setAttribute('data-file-id', file.name + file.size + file.lastModified);

                listItem.appendChild(fileName);
                listItem.appendChild(removeBtn);
                fileList.appendChild(listItem);
            });
        }
    }

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            Array.from(this.files).forEach(newFile => {
                const fileExists = allFiles.some(existingFile =>
                    existingFile.name === newFile.name &&
                    existingFile.size === newFile.size &&
                    existingFile.lastModified === newFile.lastModified
                );

                if (!fileExists) {
                    allFiles.push(newFile);
                }
            });

            updateFileList();

            const dataTransfer = new DataTransfer();
            allFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        }
    });

    fileList.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.remove-file');

        if (removeBtn) {
            e.preventDefault();
            const fileId = removeBtn.getAttribute('data-file-id');

            allFiles = allFiles.filter(file =>
                (file.name + file.size + file.lastModified) !== fileId
            );

            updateFileList();

            const dataTransfer = new DataTransfer();
            allFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;

            const event = new Event('change', { bubbles: true });
            fileInput.dispatchEvent(event);
        }
    });

    document.addEventListener('wpcf7mailsent', function() {
        allFiles = [];
        fileList.innerHTML = '';
    }, false);
});
