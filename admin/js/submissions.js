document.addEventListener('DOMContentLoaded', function() {
    // Handle delete contact
    document.querySelectorAll('.delete-contact').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this contact?')) {
                // Perform AJAX request to delete the contact
                // ...
            }
        });
    });

    // Handle copy contact
    document.querySelectorAll('.copy-contact').forEach(button => {
        button.addEventListener('click', function() {
            const email = this.getAttribute('data-email');
            const name = this.getAttribute('data-name');
            const contact = `${name} <${email}>`;
            navigator.clipboard.writeText(contact).then(() => {
                alert('Contact copied to clipboard.');
            });
        });
    });

    // Handle follow up email
    document.querySelectorAll('.follow-up').forEach(button => {
        button.addEventListener('click', function() {
            const email = this.getAttribute('data-email');
            const name = this.getAttribute('data-name');
            document.getElementById('follow-up-email').value = email;
            document.getElementById('follow-up-subject').value = `Follow Up: ${name}`;
            document.getElementById('follow-up-message').value = '';
            const followUpModal = new bootstrap.Modal(document.getElementById('follow-up-modal'));
            followUpModal.show();
        });
    });

    // Handle follow up form submission
    document.getElementById('follow-up-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const email = document.getElementById('follow-up-email').value;
        const subject = document.getElementById('follow-up-subject').value;
        const message = document.getElementById('follow-up-message').value;

        jQuery.ajax({
            url: jecContactFormData.ajax_url,
            method: 'POST',
            data: {
                action: 'send_follow_up_email',
                security: jecContactFormData.security,
                email: email,
                subject: subject,
                message: message
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data);
                    const followUpModal = bootstrap.Modal.getInstance(document.getElementById('follow-up-modal'));
                    followUpModal.hide();
                } else {
                    alert(response.data);
                }
            },
            error: function(e) {
                console.error(e);
                alert('An error occurred while sending the email.');
            }
        });
    });

    // Handle download info
    document.querySelectorAll('.download-info').forEach(button => {
        button.addEventListener('click', function() {
            const submission = JSON.parse(this.getAttribute('data-submission'));
            const content = JSON.stringify(submission, null, 2);
            const blob = new Blob([content], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'contact-info.json';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
    });

    // Handle download all as JSON
    document.getElementById('download-all-json').addEventListener('click', function() {
        const submissions = jecContactFormData.submissions;
        const content = JSON.stringify(submissions, null, 2);
        const blob = new Blob([content], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'all-submissions.json';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });

    // Handle download all as CSV
    document.getElementById('download-all-csv').addEventListener('click', function() {
        const submissions = jecContactFormData.submissions;
        const csvContent = "data:text/csv;charset=utf-8,"
            + ["ID,First Name,Last Name,Email,Company,Reason,Other Reason,Description,Copy,Created At"]
            .concat(submissions.map(submission => [
                submission.id,
                submission.first_name,
                submission.last_name,
                submission.email_address,
                submission.company_name,
                submission.contact_reason,
                submission.other_reason,
                submission.description_text,
                submission.receive_copy ? 'Yes' : 'No',
                submission.created_at
            ].join(',')))
            .join('\n');
        const encodedUri = encodeURI(csvContent);
        const a = document.createElement('a');
        a.href = encodedUri;
        a.download = 'all-submissions.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    });

    // Live search
    document.getElementById('live-search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#submissions-table-body tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(searchTerm));
            row.style.display = match ? '' : 'none';
        });
    });
});