function openEditModal(row) {
    document.getElementById('editId').value = row.id;
    document.getElementById('editCategory').value = row.category;
    document.getElementById('editBrandName').value = row.brand_name;
    document.getElementById('editProductModel').value = row.product_model;
    document.getElementById('editQuantity').value = row.quantity;
    document.getElementById('editBranch').value = row.branch;

    document.getElementById('editModal').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

function submitEdit() {
    const formData = new FormData(document.getElementById('editForm'));
    fetch('update.php', {
        method: 'POST',
        body: formData
    }).then(response => response.text())
      .then(data => {
          alert('Record updated successfully!');
          location.reload(); // Reload the page to show updated data
      }).catch(err => alert('Error: ' + err));
}
