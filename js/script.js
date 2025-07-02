// Modal Functions
function openAddModal() {
    document.getElementById('addModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('addForm').reset();
}

function openEditModal(row) {
    const id = row.dataset.id;
    const stockNo = row.querySelector('.stock_no').textContent;
    const description = row.querySelector('.description').textContent;
    const unit = row.querySelector('.unit').textContent;
    const unitCost = row.querySelector('.unit_cost').textContent;
    const quantity = row.querySelector('.quantity').textContent;

    document.getElementById('editId').value = id;
    document.getElementById('editStockNo').value = stockNo;
    document.getElementById('editDescription').value = description;
    document.getElementById('editUnit').value = unit;
    document.getElementById('editUnitCost').value = unitCost;
    document.getElementById('editQuantity').value = quantity;

    document.getElementById('editModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('editForm').reset();
}

document.getElementById('itemSearch').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase().trim();
    const tableRows = document.querySelectorAll('#itemsTable tbody tr');

    tableRows.forEach(row => {
        const stockNo = row.querySelector('.stock_no')?.textContent.toLowerCase() || '';
        const description = row.querySelector('.description')?.textContent.toLowerCase() || '';
        const unit = row.querySelector('.unit')?.textContent.toLowerCase() || '';

        const isMatch = stockNo.includes(searchValue) ||
                        description.includes(searchValue) ||
                        unit.includes(searchValue);

        row.style.display = isMatch ? '' : 'none';
    });
});


// Add Form Submit
document.getElementById('addForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = new FormData(this);
    fetch('ajax.php', {
        method: 'POST',
        body: form
    })
    .then(r => r.text())
    .then(res => {
        document.querySelector('tbody').insertAdjacentHTML('afterbegin', res);
        this.reset();
        attachEventListeners(); // reattach for new row
        closeAddModal();
    });
});

// Edit Form Submit
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const id = formData.get('id');
    const row = document.querySelector(`tr[data-id="${id}"]`);
    
    const params = new URLSearchParams();
    params.append('action', 'update');
    params.append('id', id);
    params.append('stock_no', formData.get('stock_no'));
    params.append('description', formData.get('description'));
    params.append('unit', formData.get('unit'));
    params.append('unit_cost', formData.get('unit_cost'));
    params.append('quantity', formData.get('quantity'));
    
    fetch('ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
    })
    .then(() => {
        // Update the row in the table
        row.querySelector('.stock_no').textContent = formData.get('stock_no');
        row.querySelector('.description').textContent = formData.get('description');
        row.querySelector('.unit').textContent = formData.get('unit');
        row.querySelector('.unit_cost').textContent = formData.get('unit_cost');
        row.querySelector('.quantity').textContent = formData.get('quantity');
        closeEditModal();
    });
});

function attachEventListeners() {
    document.querySelectorAll('.edit').forEach(button => {
        button.onclick = function () {
            const row = this.closest('tr');
            openEditModal(row);
        };
    });

    document.querySelectorAll('.delete').forEach(button => {
        button.onclick = function () {
            const row = this.closest('tr');
            const id = row.dataset.id;
            if (confirm('Are you sure you want to delete this item?')) {
                fetch('ajax.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=delete&id=${id}`
                }).then(() => row.remove());
            }
        };
    });
}

// Initial event binding
attachEventListeners();

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddModal();
        closeEditModal();
    }
    if (e.ctrlKey && e.key === 'n') {
        e.preventDefault();
        openAddModal();
    }
});