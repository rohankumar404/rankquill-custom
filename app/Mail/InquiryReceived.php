<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public Inquiry $inquiry;

    /**
     * Create a new message instance.
     */
    public function __construct(Inquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Inquiry: '.($this->inquiry->subject ?? 'General Contact Form'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtml(),
        );
    }

    /**
     * Build the HTML markup.
     */
    protected function buildHtml(): string
    {
        $name = e($this->inquiry->name);
        $email = e($this->inquiry->email);
        $phone = e($this->inquiry->phone ?? 'N/A');
        $company = e($this->inquiry->company ?? 'N/A');
        $subject = e($this->inquiry->subject ?? 'General Contact Form');
        $message = nl2br(e($this->inquiry->message));
        $source = e($this->inquiry->source);
        $ip = e($this->inquiry->metadata['ip'] ?? 'N/A');
        $userAgent = e($this->inquiry->metadata['user_agent'] ?? 'N/A');

        return "
            <div style=\"font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #ffffff;\">
                <h2 style=\"color: #4f46e5; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;\">New Lead Captured</h2>
                <p>You have received a new contact inquiry through the website.</p>
                
                <table style=\"width: 100%; border-collapse: collapse; margin-top: 20px;\">
                    <tr>
                        <td style=\"padding: 8px 0; font-weight: bold; width: 150px; color: #475569;\">Name:</td>
                        <td style=\"padding: 8px 0; color: #0f172a;\">{$name}</td>
                    </tr>
                    <tr>
                        <td style=\"padding: 8px 0; font-weight: bold; color: #475569;\">Email:</td>
                        <td style=\"padding: 8px 0; color: #0f172a;\"><a href=\"mailto:{$email}\">{$email}</a></td>
                    </tr>
                    <tr>
                        <td style=\"padding: 8px 0; font-weight: bold; color: #475569;\">Phone:</td>
                        <td style=\"padding: 8px 0; color: #0f172a;\">{$phone}</td>
                    </tr>
                    <tr>
                        <td style=\"padding: 8px 0; font-weight: bold; color: #475569;\">Company:</td>
                        <td style=\"padding: 8px 0; color: #0f172a;\">{$company}</td>
                    </tr>
                    <tr>
                        <td style=\"padding: 8px 0; font-weight: bold; color: #475569;\">Source:</td>
                        <td style=\"padding: 8px 0; color: #0f172a; text-transform: uppercase;\">{$source}</td>
                    </tr>
                    <tr>
                        <td style=\"padding: 8px 0; font-weight: bold; color: #475569;\">Subject:</td>
                        <td style=\"padding: 8px 0; color: #0f172a;\">{$subject}</td>
                    </tr>
                </table>

                <div style=\"margin-top: 20px; padding: 15px; background-color: #f8fafc; border-radius: 6px; border-left: 4px solid #4f46e5;\">
                    <strong style=\"color: #475569; display: block; margin-bottom: 8px;\">Message:</strong>
                    <div style=\"color: #334155; line-height: 1.6;\">{$message}</div>
                </div>

                <div style=\"margin-top: 30px; padding-top: 15px; border-top: 1px solid #f1f5f9; font-size: 11px; color: #94a3b8;\">
                    <strong>Visitor Metadata:</strong><br>
                    IP Address: {$ip}<br>
                    User Agent: {$userAgent}
                </div>
            </div>
        ";
    }
}
