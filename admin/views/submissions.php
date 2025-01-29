<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Incluir la clase de manejo de presentaciones
require_once MPC_PLUGIN_DIR . 'includes/class-contact-form-submissions.php';

// Obtener las presentaciones del formulario de la base de datos
$submissions = Contact_Form_Submissions::get_submissions();

?>
<div class="wrap">
    <h1 class="mb-4"><?php _e('Contact Form Submissions', 'jec-contact-form'); ?></h1>
    <div class="mb-3">
        <input type="text" id="live-search" class="form-control" placeholder="<?php _e('Search...', 'jec-contact-form'); ?>">
    </div>
    <div class="mb-3">
        <button id="download-all-json" class="btn btn-primary"><?php _e('Download All as JSON', 'jec-contact-form'); ?></button>
        <button id="download-all-csv" class="btn btn-secondary"><?php _e('Download All as CSV', 'jec-contact-form'); ?></button>
    </div>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th><?php _e('ID', 'jec-contact-form'); ?></th>
                <th><?php _e('First Name', 'jec-contact-form'); ?></th>
                <th><?php _e('Last Name', 'jec-contact-form'); ?></th>
                <th><?php _e('Email', 'jec-contact-form'); ?></th>
                <th><?php _e('Company', 'jec-contact-form'); ?></th>
                <th><?php _e('Reason', 'jec-contact-form'); ?></th>
                <th><?php _e('Other Reason', 'jec-contact-form'); ?></th>
                <th><?php _e('Description', 'jec-contact-form'); ?></th>
                <th><?php _e('Copy', 'jec-contact-form'); ?></th>
                <th><?php _e('Created At', 'jec-contact-form'); ?></th>
                <th><?php _e('Form URL', 'jec-contact-form'); ?></th>
                <th><?php _e('Actions', 'jec-contact-form'); ?></th>
                <th><?php _e('Download Info', 'jec-contact-form'); ?></th>
            </tr>
        </thead>
        <tbody id="submissions-table-body">
            <?php if ( $submissions ) : ?>
                <?php foreach ( $submissions as $submission ) : ?>
                    <tr>
                        <td><?php echo esc_html( $submission->id ); ?></td>
                        <td><?php echo esc_html( $submission->first_name ); ?></td>
                        <td><?php echo esc_html( $submission->last_name ); ?></td>
                        <td><?php echo esc_html( $submission->email_address ); ?></td>
                        <td><?php echo esc_html( $submission->company_name ); ?></td>
                        <td><?php echo esc_html( $submission->contact_reason ); ?></td>
                        <td><?php echo esc_html( $submission->other_reason ); ?></td>
                        <td><?php echo esc_html( $submission->description_text ); ?></td>
                        <td><?php echo esc_html( $submission->receive_copy ? __('Yes', 'jec-contact-form') : __('No', 'jec-contact-form') ); ?></td>
                        <td><?php echo esc_html( $submission->created_at ); ?></td>
                        <td><?php echo esc_html( $submission->form_url ); ?></td>
                        <td>
                            <button class="btn btn-danger delete-contact" data-id="<?php echo esc_attr( $submission->id ); ?>" title="<?php _e('Delete', 'jec-contact-form'); ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                            <button class="btn btn-info copy-contact" data-email="<?php echo esc_attr( $submission->email_address ); ?>" data-name="<?php echo esc_attr( $submission->first_name . ' ' . $submission->last_name ); ?>" title="<?php _e('Copy', 'jec-contact-form'); ?>">
                                <i class="bi bi-clipboard"></i>
                            </button>
                            <button class="btn btn-warning follow-up" data-email="<?php echo esc_attr( $submission->email_address ); ?>" data-name="<?php echo esc_attr( $submission->first_name . ' ' . $submission->last_name ); ?>" title="<?php _e('Follow Up', 'jec-contact-form'); ?>">
                                <i class="bi bi-envelope"></i>
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-success download-info" data-submission='<?php echo json_encode($submission); ?>' title="<?php _e('Download', 'jec-contact-form'); ?>">
                                <i class="bi bi-download"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="13"><?php _e('No submissions found.', 'jec-contact-form'); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal for Follow Up Email -->
<div class="modal fade" id="follow-up-modal" tabindex="-1" aria-labelledby="followUpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="followUpModalLabel"><?php _e('Send Follow Up Email', 'jec-contact-form'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="follow-up-form">
                    <input type="hidden" id="follow-up-email" name="email">
                    <div class="mb-3">
                        <label for="follow-up-subject" class="form-label"><?php _e('Subject', 'jec-contact-form'); ?></label>
                        <input type="text" id="follow-up-subject" name="subject" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="follow-up-message" class="form-label"><?php _e('Message', 'jec-contact-form'); ?></label>
                        <textarea id="follow-up-message" name="message" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><?php _e('Send', 'jec-contact-form'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Incluir los scripts y estilos -->
<?php
wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', array(), null, true);
wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
wp_enqueue_script('jec-contact-form-submissions', plugin_dir_url(__FILE__) . '../js/submissions.js', array('jquery', 'bootstrap-js'), null, true);
wp_enqueue_style('jec-contact-form-submissions', plugin_dir_url(__FILE__) . '../css/submissions.css');

// Pasar datos de PHP a JavaScript
wp_localize_script('jec-contact-form-submissions', 'jecContactFormData', array(
    'submissions' => $submissions
));
?>