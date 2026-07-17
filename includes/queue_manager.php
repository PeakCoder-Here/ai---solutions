<?php
/**
 * queue_manager.php — Transactional email queue & dispatch
 *
 * Reconstructed file — missing after the project's drive move (required
 * by contact.php, schedule-demo.php and join-events.php but not present
 * anywhere in the project).
 *
 * Design: every queue_*() call logs the intended email into the
 * `email_queue` MongoDB collection (so nothing is ever lost, and forms
 * never fatal-error even if mail sending fails), then makes a
 * best-effort attempt to actually send it via PHPMailer/SMTP.
 *
 * SMTP is OFF by default — forms will keep working (returning
 * $emailQueued = false) until you configure it. To enable outgoing
 * mail, define these constants (e.g. in includes/config.php) BEFORE
 * this file is first required:
 *
 *   define('SMTP_HOST', 'smtp.yourprovider.com');
 *   define('SMTP_PORT', 587);
 *   define('SMTP_USER', 'you@example.com');
 *   define('SMTP_PASS', 'your-app-password');
 *   define('SMTP_FROM', 'you@example.com');
 *   define('SMTP_FROM_NAME', 'AI-Solutions');
 *   define('ADMIN_NOTIFY_EMAIL', 'admin@ai-solutions.co.uk');
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

if (!defined('SMTP_HOST'))          define('SMTP_HOST', '');
if (!defined('SMTP_PORT'))          define('SMTP_PORT', 587);
if (!defined('SMTP_USER'))          define('SMTP_USER', '');
if (!defined('SMTP_PASS'))          define('SMTP_PASS', '');
if (!defined('SMTP_FROM'))          define('SMTP_FROM', 'no-reply@ai-solutions.co.uk');
if (!defined('SMTP_FROM_NAME'))     define('SMTP_FROM_NAME', 'AI-Solutions');
if (!defined('ADMIN_NOTIFY_EMAIL')) define('ADMIN_NOTIFY_EMAIL', 'admin@ai-solutions.co.uk');

class QueueManager
{
    /** Resolve the active MongoDB\Database instance created by includes/db.php */
    private static function db()
    {
        global $db;
        return $db ?? null;
    }

    /** Always-log step — runs regardless of whether sending succeeds */
    private static function log(string $type, string $refId, string $to, string $subject, string $status, string $note = ''): void
    {
        $database = self::db();
        if (!$database) {
            return;
        }
        try {
            $database->email_queue->insertOne([
                'type'       => $type,
                'ref_id'     => $refId,
                'to'         => $to,
                'subject'    => $subject,
                'status'     => $status,
                'note'       => $note,
                'created_at' => new \MongoDB\BSON\UTCDateTime(),
            ]);
        } catch (\Throwable $e) {
            // Logging must never break the request that triggered it.
        }
    }

    /** Shared send routine used by every public queue_*() method below */
    private static function send(string $type, string $refId, string $to, string $toName, string $subject, string $htmlBody): bool
    {
        if (empty(SMTP_HOST) || empty($to)) {
            self::log($type, $refId, $to, $subject, 'skipped_no_smtp');
            return false;
        }

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->Port       = SMTP_PORT;
            $mail->SMTPAuth   = !empty(SMTP_USER);
            if ($mail->SMTPAuth) {
                $mail->Username = SMTP_USER;
                $mail->Password = SMTP_PASS;
            }
            $mail->SMTPSecure = ((int) SMTP_PORT === 465)
                ? PHPMailer::ENCRYPTION_SMTPS
                : PHPMailer::ENCRYPTION_STARTTLS;

            $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
            $mail->addAddress($to, $toName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = strip_tags(str_replace('<br>', "\n", $htmlBody));

            $mail->send();
            self::log($type, $refId, $to, $subject, 'sent');
            return true;
        } catch (PHPMailerException | \Exception $e) {
            self::log($type, $refId, $to, $subject, 'failed', $mail->ErrorInfo ?? $e->getMessage());
            return false;
        }
    }

    // ── Contact form (contact.php) ───────────────────────────────────
    public static function queue_contact_acknowledgement(array $data, string $refId): bool
    {
        $subject = "We've received your enquiry — AI-Solutions";
        $body =
            '<p>Hi ' . htmlspecialchars($data['name'] ?? '') . ',</p>' .
            '<p>Thanks for reaching out to AI-Solutions. We\'ve received your enquiry (ref <strong>' . htmlspecialchars($refId) . '</strong>) and will respond within 24 hours.</p>' .
            '<p><strong>Your message:</strong><br>' . nl2br(htmlspecialchars($data['job_details'] ?? '')) . '</p>' .
            '<p>— The AI-Solutions Team</p>';
        return self::send('contact_acknowledgement', $refId, $data['email'] ?? '', $data['name'] ?? '', $subject, $body);
    }

    // ── Demo requests (schedule-demo.php) ────────────────────────────
    public static function queue_demo_confirmation(array $data, string $refId): bool
    {
        $subject = 'Your demo request is confirmed — AI-Solutions';
        $body =
            '<p>Hi ' . htmlspecialchars($data['name'] ?? '') . ',</p>' .
            '<p>Thanks for requesting a demo of <strong>' . htmlspecialchars($data['interest_type'] ?? 'our solutions') . '</strong> (ref <strong>' . htmlspecialchars($refId) . '</strong>). Our team will contact you shortly to schedule a time.</p>' .
            '<p>— The AI-Solutions Team</p>';
        return self::send('demo_confirmation', $refId, $data['email'] ?? '', $data['name'] ?? '', $subject, $body);
    }

    public static function queue_demo_followup(array $data, string $refId): bool
    {
        $subject = 'Following up on your AI-Solutions demo request';
        $body =
            '<p>Hi ' . htmlspecialchars($data['name'] ?? '') . ',</p>' .
            '<p>Just checking in about your demo request (ref <strong>' . htmlspecialchars($refId) . '</strong>). Let us know if you have any questions in the meantime.</p>' .
            '<p>— The AI-Solutions Team</p>';
        return self::send('demo_followup', $refId, $data['email'] ?? '', $data['name'] ?? '', $subject, $body);
    }

    // ── Event registrations (join-events.php) ────────────────────────
    public static function queue_event_registration_confirmation(array $data, string $refId): bool
    {
        $eventName = $data['event_name'] ?? 'our event';
        $subject   = "You're registered! — " . $eventName;
        $body =
            '<p>Hi ' . htmlspecialchars($data['name'] ?? '') . ',</p>' .
            '<p>You\'re confirmed for <strong>' . htmlspecialchars($eventName) . '</strong> (ref <strong>' . htmlspecialchars($refId) . '</strong>). We look forward to seeing you there.</p>' .
            '<p>— The AI-Solutions Team</p>';
        return self::send('event_registration_confirmation', $refId, $data['email'] ?? '', $data['name'] ?? '', $subject, $body);
    }

    public static function queue_event_follow_emails(array $data, string $refId): bool
    {
        $eventName = $data['event_name'] ?? 'our event';
        $subject   = 'See you soon at ' . $eventName;
        $body =
            '<p>Hi ' . htmlspecialchars($data['name'] ?? '') . ',</p>' .
            '<p>This is a reminder about your upcoming registration for <strong>' . htmlspecialchars($eventName) . '</strong> (ref <strong>' . htmlspecialchars($refId) . '</strong>).</p>' .
            '<p>— The AI-Solutions Team</p>';
        return self::send('event_follow_up', $refId, $data['email'] ?? '', $data['name'] ?? '', $subject, $body);
    }

    // ── Orders (checkout.php) ─────────────────────────────────────────
    public static function queue_order_confirmation(array $order, string $refId): bool
    {
        $subject = 'Invoice ' . $refId . ' — AI-Solutions';
        $rows = '';
        foreach ($order['line_items'] ?? [] as $li) {
            $rows .= '<tr><td style="padding:8px;border-bottom:1px solid #E2E8F0;">' . htmlspecialchars($li['label'] ?? '') . '</td>' .
                     '<td style="padding:8px;border-bottom:1px solid #E2E8F0;text-align:right;">£' . number_format((float)($li['amount'] ?? 0), 2) . '</td></tr>';
        }
        $discountRow = !empty($order['discount'])
            ? '<div style="display:flex;justify-content:space-between;padding:.2rem 0;color:#16A34A;"><span>Annual discount</span><span>-£' . number_format((float)$order['discount'], 2) . '</span></div>'
            : '';
        $invoiceUrl = rtrim(SITE_URL, '/') . '/invoice.php?order_id=' . urlencode($refId);

        $body =
            '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;">' .
            '<div style="border-bottom:3px solid #4F46E5;padding-bottom:12px;margin-bottom:16px;">' .
                '<strong style="font-size:18px;color:#1E1B4B;">AI-Solutions</strong><br>' .
                '<span style="font-size:13px;color:#64748B;">Invoice #' . htmlspecialchars($refId) . '</span>' .
            '</div>' .
            '<p>Hi ' . htmlspecialchars($order['customer_name'] ?? '') . ',</p>' .
            '<p>Thanks for your order! Here is your invoice for the <strong>' . htmlspecialchars($order['plan'] ?? '') . ' plan</strong>.</p>' .
            '<table style="width:100%;border-collapse:collapse;margin:16px 0;">' .
                '<tr><th style="text-align:left;padding:8px;border-bottom:2px solid #CBD5E1;font-size:12px;color:#64748B;">Description</th><th style="text-align:right;padding:8px;border-bottom:2px solid #CBD5E1;font-size:12px;color:#64748B;">Amount</th></tr>' .
                $rows .
            '</table>' .
            '<div style="max-width:260px;margin-left:auto;">' .
                '<div style="display:flex;justify-content:space-between;padding:.2rem 0;"><span>Subtotal</span><span>£' . number_format((float)($order['subtotal'] ?? 0), 2) . '</span></div>' .
                $discountRow .
                '<div style="display:flex;justify-content:space-between;padding:.4rem 0;font-weight:bold;font-size:16px;border-top:2px solid #CBD5E1;margin-top:4px;"><span>' . htmlspecialchars($order['billing_cycle'] ?? 'Monthly') . ' Total</span><span>£' . number_format((float)($order['grand_total'] ?? 0), 2) . '</span></div>' .
            '</div>' .
            '<p style="margin-top:16px;">Payment method: ' . htmlspecialchars($order['payment_method'] ?? '') . '</p>' .
            '<p><a href="' . htmlspecialchars($invoiceUrl) . '" style="display:inline-block;background:#4F46E5;color:#fff;padding:10px 18px;border-radius:6px;text-decoration:none;">View / Print Invoice</a></p>' .
            '<p>Our team will be in touch shortly to complete onboarding.</p>' .
            '<p>— The AI-Solutions Team</p>' .
            '</div>';
        return self::send('order_confirmation', $refId, $order['customer_email'] ?? '', $order['customer_name'] ?? '', $subject, $body);
    }

    /** Render a nested array field as a readable string for the admin table
     *  (e.g. order line_items → "Base plan (£299), Slack (£79)") instead of raw JSON. */
    private static function format_array_field(array $arr): string
    {
        if (empty($arr)) return '—';
        $parts = [];
        foreach ($arr as $item) {
            if (is_array($item) && isset($item['label'])) {
                $amount = isset($item['amount']) ? ' (£' . number_format((float) $item['amount'], 2) . ')' : '';
                $parts[] = $item['label'] . $amount;
            } elseif (is_scalar($item)) {
                $parts[] = (string) $item;
            } else {
                $parts[] = json_encode($item);
            }
        }
        return implode(', ', $parts);
    }

    // ── Internal admin notification (used by all forms above) ────────
    public static function queue_admin_notification(string $type, array $data, string $refId): bool
    {
        $subject = 'New ' . str_replace('_', ' ', $type) . ' — ref ' . $refId;
        $rows = '';
        foreach ($data as $k => $v) {
            if ($v instanceof \MongoDB\BSON\UTCDateTime) {
                $v = $v->toDateTime()->format('d M Y H:i');
            } elseif (is_array($v)) {
                $v = self::format_array_field($v);
            } elseif (is_bool($v)) {
                $v = $v ? 'yes' : 'no';
            }
            $label = ucwords(str_replace('_', ' ', (string) $k));
            $rows .= '<tr><td style="padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;color:#475569;white-space:nowrap;">' . htmlspecialchars($label) . '</td>' .
                     '<td style="padding:8px;border-bottom:1px solid #E2E8F0;">' . htmlspecialchars((string) $v) . '</td></tr>';
        }
        $typeLabel = ucwords(str_replace('_', ' ', $type));
        $body =
            '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;">' .
            '<div style="border-bottom:3px solid #4F46E5;padding-bottom:12px;margin-bottom:16px;">' .
                '<strong style="font-size:16px;color:#1E1B4B;">New ' . htmlspecialchars($typeLabel) . '</strong><br>' .
                '<span style="font-size:13px;color:#64748B;">Reference: ' . htmlspecialchars($refId) . '</span>' .
            '</div>' .
            '<table style="width:100%;border-collapse:collapse;">' . $rows . '</table>' .
            '<p style="margin-top:16px;font-size:13px;color:#64748B;">Log in to the admin panel to review or action this submission.</p>' .
            '</div>';
        return self::send('admin_notification', $refId, ADMIN_NOTIFY_EMAIL, 'Admin', $subject, $body);
    }
}
