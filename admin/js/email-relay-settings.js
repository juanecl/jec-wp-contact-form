document.addEventListener('DOMContentLoaded', function() {
    const emailProviderSelect = document.getElementById('email_provider');
    const sendgridSettings = document.getElementById('sendgrid-settings');
    const brevoSettings = document.getElementById('brevo-settings');

    function toggleSettings() {
        if (emailProviderSelect.value === 'sendgrid') {
            sendgridSettings.style.display = 'block';
            brevoSettings.style.display = 'none';
        } else if (emailProviderSelect.value === 'brevo') {
            sendgridSettings.style.display = 'none';
            brevoSettings.style.display = 'block';
        }
    }

    if (emailProviderSelect) {
        emailProviderSelect.addEventListener('change', toggleSettings);
        toggleSettings();
    }
});