document.addEventListener('DOMContentLoaded', function() {
    var reasonSelect = document.getElementById('contact_reason');
    var reasonOtherGroup = document.getElementById('reason_other_group');
    reasonSelect.addEventListener('change', function() {
        if (this.value === 'Other') {
            reasonOtherGroup.style.display = 'block';
        } else {
            reasonOtherGroup.style.display = 'none';
        }
    });

    // Bootstrap validation
    var forms = document.getElementsByClassName('needs-validation');
    Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                event.preventDefault();
                // Handle form submission with AJAX
                var formData = new FormData(form);
                var action = form.getAttribute('action'); // Obtener la URL de acción del formulario
                fetch(action, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Response:', data);
                    if (data.success) {
                        alert('Form submitted successfully!');
                        form.reset();
                        form.classList.remove('was-validated');
                    } else {
                        alert('There was an error submitting the form.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('There was an error submitting the form.');
                });
            }
            form.classList.add('was-validated');
        }, false);
    });
});