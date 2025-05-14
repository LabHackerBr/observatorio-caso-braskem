document.addEventListener('DOMContentLoaded', function() {
    let allFiles = [];
    const fileInput = document.getElementById('custom-file-upload');
    const fileList = document.querySelector('.uploaded-files-list');

    if (!fileInput || !fileList) return;

    // Função para atualizar a lista visual
    function updateFileList() {
        fileList.innerHTML = '';

        if (allFiles.length > 0) {
            allFiles.forEach((file, index) => {
                const listItem = document.createElement('li');
                listItem.className = 'file-item';
                listItem.setAttribute('data-file-id', file.name + file.size + file.lastModified);

                // Nome do arquivo
                const fileName = document.createElement('span');
                fileName.textContent = file.name;

                // Botão para remover
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

    // Evento quando novos arquivos são selecionados
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            // Adiciona apenas arquivos novos (evita duplicação)
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

    // Evento para remover arquivos individuais
    fileList.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.remove-file');

        if (removeBtn) {
            e.preventDefault();
            const fileId = removeBtn.getAttribute('data-file-id');

            // Remove o arquivo pelo ID único
            allFiles = allFiles.filter(file =>
                (file.name + file.size + file.lastModified) !== fileId
            );

            updateFileList();

            const dataTransfer = new DataTransfer();
            allFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;

            // Dispara evento de mudança para o CF7
            const event = new Event('change', { bubbles: true });
            fileInput.dispatchEvent(event);
        }
    });

    // Limpar lista após envio
    document.addEventListener('wpcf7mailsent', function() {
        allFiles = [];
        fileList.innerHTML = '';
    }, false);
});
