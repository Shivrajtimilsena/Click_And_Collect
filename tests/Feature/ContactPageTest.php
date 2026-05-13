<?php

namespace Tests\Feature;

use App\Mail\ContactEnquiryMail;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    public function test_contact_page_loads(): void
    {
        $response = $this->get(route('contact'));

        $response->assertOk();
        $response->assertSee('Send An Enquiry');
    }

    public function test_contact_form_sends_enquiry(): void
    {
        Mail::fake();

        $response = $this->post(route('contact.submit'), [
            'name' => 'Jamie Carter',
            'email' => 'jamie@example.com',
            'phone' => '+44 7700 900123',
            'customer_type' => 'customer',
            'subject' => 'Collection timing for order CC-2048',
            'order_reference' => 'CC-2048',
            'preferred_contact' => 'email',
            'message' => 'I need to confirm whether my collection window can be moved to the later afternoon slot tomorrow.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Mail::assertSent(ContactEnquiryMail::class, function (ContactEnquiryMail $mail) {
            return $mail->enquiry['email'] === 'jamie@example.com'
                && $mail->enquiry['subject'] === 'Collection timing for order CC-2048';
        });
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $response = $this->from(route('contact'))->post(route('contact.submit'), [
            'name' => '',
            'email' => 'not-an-email',
            'customer_type' => '',
            'subject' => '',
            'preferred_contact' => 'fax',
            'message' => 'too short',
        ]);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHasErrors([
            'name',
            'email',
            'customer_type',
            'subject',
            'preferred_contact',
            'message',
        ]);
    }
}
