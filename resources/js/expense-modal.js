document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('editExpenseModal');
    const form = document.getElementById('editExpenseForm');
    const selectEl = $('#edit_expense_category');

    // 1. Fill data when modal open
    if (modal) {
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const row = button.closest('tr');
            const categoryId = row.getAttribute('data-category-id');

            // Input value
            document.getElementById('edit-expense-id').value = button.getAttribute('data-id');
            document.getElementById('edit-unit').value = row.cells[2].innerText.trim();
            document.getElementById('edit-quantity').value = row.cells[3].innerText.trim();
            document.getElementById('edit-price').value = row.cells[4].innerText.trim().replace(/,/g, '');
            document.getElementById('edit-cost').value = row.cells[5].innerText.trim().replace(/,/g, '');

            // reset Select2
            if (selectEl.hasClass('select2-hidden-accessible')) {
                selectEl.select2('destroy');
            }

            // new Select2
            selectEl.select2({
                dropdownParent: $('#editExpenseModal'),
                width: '100%'
            });

            setTimeout(() => {
                selectEl.val(categoryId).trigger('change.select2');
            }, 100);
        });
    }

    // 2. Form Submit Logic
    if (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const id = document.getElementById('edit-expense-id').value;
            const updateUrl = `/work_plans/update-office-expense/${id}`;
            const catSelect = document.getElementById('edit_expense_category') || form.querySelector('[name="expense_category"]');
            let categoryValue = '';
            if (catSelect) {
                categoryValue = (typeof $ !== 'undefined' && $(catSelect).hasClass('select2-hidden-accessible')) ? $(catSelect).val() : catSelect.value;
            }

            const payload = {
                _method: 'PUT',
                unit: document.getElementById('edit-unit').value,
                expense_category_id: categoryValue,
                quantity: document.getElementById('edit-quantity').value,
                unit_price: document.getElementById('edit-price').value,
                estimated_cost: document.getElementById('edit-cost').value,
                work_plan_id: document.querySelector('#work_plan_id').value,
            };

            fetch(updateUrl, {
                method: 'POST',
                body: JSON.stringify(payload),
                headers: {
                    'X-CSRF-TOKEN': ExpenseManager.token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                        row.setAttribute('data-category-id', data.data.expense_category_id);
                        row.cells[1].innerHTML = `<span class="badge bg-label-primary">${data.data.expense_category_name}</span>`;
                        row.cells[2].innerText = data.data.unit;
                        row.cells[3].innerText = data.data.quantity;
                        row.cells[4].innerText = Number(data.data.unit_price).toLocaleString();
                        row.cells[5].innerText = Number(data.data.estimated_cost).toLocaleString();
                    }

                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) modalInstance.hide();

                    Swal.fire({ icon: 'success', title: data.message, showConfirmButton: false, timer: 1500 });
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                console.error("Error:", err);
                alert("error");
            });
        });
    }
});
