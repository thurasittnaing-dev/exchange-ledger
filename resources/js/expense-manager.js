export const ExpenseManager = {
    mmDigits: ['၀', '၁', '၂', '၃', '၄', '၅', '၆', '၇', '၈', '၉'],
    token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),

    toMyanmarNum(num) {
        return num.toString().split('').map(digit => this.mmDigits[digit] || digit).join('');
    },

    reIndexRows(tbody) {
        let indexCounter = 1;
        tbody.querySelectorAll('tr').forEach((row) => {
            const firstTd = row.querySelector('td:first-child');
            if (!firstTd) return;
            const finalNum = this.toMyanmarNum(indexCounter) + '။';
            if (row.id === 'new-expense-row') {
                const spanInside = firstTd.querySelector('span');
                if (spanInside) spanInside.textContent = finalNum;
            } else {
                firstTd.textContent = finalNum;
                indexCounter++;
            }
        });
    },
    store(btn) {
        const row = btn.closest('tr');
        const tbody = row.closest('tbody');
        const catSelect = row.querySelector('#expense_category');
        const storeUrl = btn.getAttribute('data-store-url');

        const payload = {
            expense_category_id: (typeof $ !== 'undefined' && $(catSelect).hasClass('select2-hidden-accessible')) ? $(catSelect).val() : catSelect.value,
            quantity: row.querySelector('#quantity').value,
            unit_price: row.querySelector('#unit_price').value,
            estimated_cost: row.querySelector('#estimated_cost').value,
            work_plan_id: row.querySelector('#work_plan_id').value,
            unit: row.querySelector('#unit').value
        };

        fetch(storeUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': this.token },
            body: JSON.stringify(payload)
        })
        .then(res => {
            row.querySelectorAll('.error-msg').forEach(el => el.classList.add('d-none'));
            row.querySelectorAll('.form-control, .form-select').forEach(el => el.classList.remove('is-invalid'));

            if (res.status === 422) {
                return res.json().then(errData => {
                    Object.keys(errData.errors).forEach(key => {
                        const errEl = row.querySelector(`#error-${key}`);
                        if (errEl) { errEl.textContent = `* ${errData.errors[key][0]}`; errEl.classList.remove('d-none'); }
                        const inputId = key === 'expense_category_id' ? 'expense_category' : key;
                        row.querySelector(`#${inputId}`)?.classList.add('is-invalid');
                    });
                    throw new Error('Validation failed');
                });
            }
            if (!res.ok) throw new Error('error1');
            return res.json();
        })
        .then(data => {
            if (!data.success) return alert('Error: ' + data.message);

            const saved = data.data;
            const selText = (typeof $ !== 'undefined' && $(catSelect).hasClass('select2-hidden-accessible')) ? $(catSelect).find(':selected').text() : catSelect.options[catSelect.selectedIndex].text;

            const newRowHtml = `
                <tr data-id="${saved.id}">
                    <td class="fw-semibold index-col"></td>
                    <td><span class="badge bg-label-primary">${saved.expense_category_name ?? selText}</span></td>
                    <td>${saved.unit ?? ''}</td>
                    <td>${saved.quantity}</td>
                    <td>${Number(saved.unit_price).toLocaleString()}</td>
                    <td class="fw-semibold">${Number(saved.estimated_cost).toLocaleString()}</td>
                    <td class="text-center">
                      <button type="button" class="btn btn-sm btn-icon btn-outline-warning p-0 edit-expense-btn" style="width: 28px; height: 28px;" data-bs-toggle="modal" data-bs-target="#editExpenseModal" data-id="${saved.id}">
                            <i class="ti ti-edit icon-font"></i>
                        </button>
                        <button type="button" onclick="ExpenseManager.delete(this)" class="btn btn-sm btn-icon btn-outline-danger p-0" data-id="${saved.id}" style="width: 28px; height: 28px;" title="Delete">
                            <i class="ti ti-trash icon-font"></i>
                        </button>
                    </td>
                </tr>`;

            row.insertAdjacentHTML('beforebegin', newRowHtml);
            this.reIndexRows(tbody);

            row.querySelectorAll('input:not([type="hidden"])').forEach(input => input.value = '');
            if (typeof $ !== 'undefined' && $(catSelect).hasClass('select2-hidden-accessible')) $(catSelect).val(null).trigger('change');
            else catSelect.value = '';
            catSelect.focus();


        })
        .catch(err => { if (err.message !== 'Validation failed') alert('An error occurred.'); });
    },

    delete(btn) {
    if (typeof Swal === 'undefined') {
        if (!confirm('Are you sure?')) return;
        this.executeDelete(btn);
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ea5455', // Red button color (Sneat / Bootstrap style)
        cancelButtonColor: '#8592a3',  // Gray button color
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        customClass: {
            confirmButton: 'btn btn-danger me-1',
            cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                this.executeDelete(btn);
            }
        });
    },

    executeDelete(btn) {
        const row = btn.closest('tr');
        const tbody = row.closest('tbody');
        const id = btn.getAttribute('data-id');

        const deleteUrl = btn.getAttribute('data-delete-url');

        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': this.token
            }
        })
        .then(res => { if (!res.ok) throw new Error(); return res.json(); })
        .then(data => {
            if (data.success) {
                row.remove();
                this.reIndexRows(tbody);

                Swal.fire({
                    icon: 'success',
                    title: data.message, // "Deleted Successfully."
                    showConfirmButton: false,
                    timer: 1500,
                    customClass: { confirmButton: 'btn btn-success' }
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message });
            }
        })
        .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while deleting.' });
        });
    }
    };
