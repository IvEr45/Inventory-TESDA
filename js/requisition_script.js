function clearSlip() {
            if (confirm('Are you sure you want to clear the slip? All entered data will be lost.')) {
                const form = document.getElementById('requisitionForm');

                // Reset standard input fields
                form.querySelectorAll('input[type="text"], input[type="number"], textarea').forEach(input => input.value = '');
                form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
                form.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false); // <-- ADD THIS
                form.querySelectorAll('input[type="date"]').forEach(input => input.value = '');

                // Reset formChanged flag
                formChanged = false;
            }
        }
        document.getElementById('itemSearch').addEventListener('input', function () {
            const searchValue = this.value.toLowerCase().trim();
            const tableRows = document.querySelectorAll('.main-table tbody tr');

            tableRows.forEach(row => {
                const stockNo = row.cells[0]?.textContent.toLowerCase() || '';
                const unit = row.cells[1]?.textContent.toLowerCase() || '';
                const description = row.cells[2]?.textContent.toLowerCase() || '';

                const isMatch =
                    stockNo.includes(searchValue) ||
                    unit.includes(searchValue) ||
                    description.includes(searchValue);

                // Skip filtering for empty/padding rows
                const isEmptyRow = [...row.cells].every(cell => cell.textContent.trim() === '');
                if (isEmptyRow) {
                    row.style.display = '';
                    return;
                }

                row.style.display = isMatch ? '' : 'none';
            });
        });


        // Auto-save functionality (optional)
        document.getElementById('requisitionForm').addEventListener('input', function() {
            // You can add auto-save functionality here if needed
        });

        // Prevent form submission on Enter key in input fields
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName === 'INPUT' && e.target.type !== 'submit') {
                e.preventDefault();
                // Move to next input field
                const inputs = Array.from(document.querySelectorAll('input'));
                const currentIndex = inputs.indexOf(e.target);
                if (currentIndex < inputs.length - 1) {
                    inputs[currentIndex + 1].focus();
                }
            }
        });

        // Confirm before page unload if form has changes
        let formChanged = false;
        document.getElementById('requisitionForm').addEventListener('input', function() {
            formChanged = true;
        });

        document.getElementById('requisitionForm').addEventListener('submit', function() {
            formChanged = false;
        });

        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });