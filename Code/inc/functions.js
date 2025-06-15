document.addEventListener('DOMContentLoaded', function () {
    // Image preview (upload page)
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('preview');

    if (imageInput && preview) {
        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        });
    }

    // Outfit change buttons
document.querySelectorAll('.change-btn').forEach(button => {
    button.addEventListener('click', function () {
        const category = this.getAttribute('data-category');
        const currentId = this.getAttribute('data-id');
        const occasion = document.getElementById('occasion')?.value || '';

        fetch(`../../inc/get_items_by_category.php?category=${category}&occasion=${occasion}`)
            .then(response => response.json())
            .then(data => {
                const targetCard = this.closest('.clothing-item');
                showPopup(data, category, targetCard, currentId);
            });
    });
});

});

function showPopup(items, category, targetCard, currentId) {
    const overlay = document.getElementById('popupOverlay');
    const grid = document.getElementById('popupGrid');
    grid.innerHTML = '';

    items.forEach(item => {
        if (item.id === parseInt(currentId)) return;

        const img = document.createElement('img');
        img.src = item.image_path;
        img.classList.add('popup-item');
        img.onclick = () => {
            targetCard.querySelector('img').src = item.image_path;
            closePopup();
        };

        grid.appendChild(img);
    });

    overlay.style.display = 'flex';
}

function closePopup() {
    document.getElementById('popupOverlay').style.display = 'none';
}


document.addEventListener('DOMContentLoaded', function () {
    const saveBtn = document.getElementById('saveOutfitBtn');
    if (saveBtn) {
        saveBtn.addEventListener('click', function () {
            const outfitData = {
                occasion: document.getElementById('occasion').value,
                top: document.querySelector('[data-category="top"]')?.closest('.clothing-item')?.querySelector('img')?.src || null,
                bottom: document.querySelector('[data-category="bottom"]')?.closest('.clothing-item')?.querySelector('img')?.src || null,
                dress: document.querySelector('[data-category="dress"]')?.closest('.clothing-item')?.querySelector('img')?.src || null,
                shoes: document.querySelector('[data-category="shoes"]')?.closest('.clothing-item')?.querySelector('img')?.src || null
            };

            fetch('../../inc/save_outfit.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(outfitData)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert("Outfit opgeslagen!");
                } else {
                    alert("Fout bij opslaan: " + (result.error || 'Onbekende fout'));
                }
            })
            .catch(() => alert("Er ging iets mis met het opslaan."));
        });
    }
});

