<?php
/**
 * This file displays the contact form submissions in the WordPress admin area.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $wpdb;
$table_name = $wpdb->prefix . 'contact_form_submissions';

/**
 * Fetches the contact form submissions from the database.
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 * @param string $table_name The name of the table to fetch submissions from.
 * @return array The list of submissions.
 */
function get_contact_form_submissions( $table_name ) {
    global $wpdb;
    return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name ORDER BY created_at DESC" ) );
}

/**
 * Outputs the submissions as an XLS file.
 *
 * @param array $submissions The list of submissions to output.
 */
function download_submissions_as_xls( $submissions ) {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=submissions.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "First Name\tLast Name\tEmail\tCompany\tReason\tDescription\n";
    foreach ( $submissions as $submission ) {
        echo esc_html( $submission->first_name ) . "\t" . esc_html( $submission->last_name ) . "\t" . esc_html( $submission->email ) . "\t" . esc_html( $submission->company ) . "\t" . esc_html( $submission->reason ) . "\t" . esc_html( $submission->description ) . "\n";
    }
    exit;
}

$submissions = get_contact_form_submissions( $table_name );

if ( isset( $_POST['download_xls'] ) ) {
    download_submissions_as_xls( $submissions );
}

/**
 * Renders the submissions table.
 *
 * @param array $submissions The list of submissions to render.
 */
function render_submissions_table( $submissions ) {
    ?>
    <table class="widefat fixed">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Company</th>
                <th>Reason</th>
                <th>Description</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ( $submissions ) : ?>
                <?php foreach ( $submissions as $submission ) : ?>
                    <tr>
                        <td><?php echo esc_html( $submission->first_name ); ?></td>
                        <td><?php echo esc_html( $submission->last_name ); ?></td>
                        <td><?php echo esc_html( $submission->email ); ?></td>
                        <td><?php echo esc_html( $submission->company ); ?></td>
                        <td><?php echo esc_html( $submission->reason ); ?></td>
                        <td><?php echo esc_html( $submission->description ); ?></td>
                        <td><?php echo esc_html( $submission->created_at ); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7">No submissions available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php
}
?>

<div class="wrap">
    <h1>Contact Form Submissions</h1>
    <form method="post">
        <input type="submit" name="download_xls" class="button button-primary" value="Download XLS">
    </form>
    <?php render_submissions_table( $submissions ); ?>
</div>