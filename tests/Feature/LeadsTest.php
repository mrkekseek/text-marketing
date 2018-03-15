<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Notification;
use App\Http\Services\LinksService;
use App\Events\FirstLead;
use App\Jobs\SendFirstLead;
use App\Jobs\SendLeadText;
use App\Jobs\SendFollowUpText;
use App\Notifications\SendFirstLeadForAdmin;

class LeadsTest extends TestCase
{
    use RefreshDatabase;

    public $owner;
    public $user;
    public $link;
    public $homeadvisor;

    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');

        $this->owner = factory(\App\User::class)
            ->create([
                'owner' => true,
            ]);
        
        $this->user = factory(\App\User::class)
            ->create();

        $this->link = factory(\App\Link::class)
            ->create([
                'users_id' => $this->user->id,
                'code' => LinksService::code($this->user),
            ]);

        $this->homeadvisor = factory(\App\Homeadvisor::class)
            ->create([
                'users_id' => $this->user->id,
            ]);
    }

    public function testFirstLeadEvent()
    {
        Event::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '2222222222';
        $email = 'info@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));

        $user = $this->user;

        Event::assertDispatched(FirstLead::class);
    }

    public function testFirstLeadExists()
    {
        $this->client = factory(\App\Client::class)
            ->create([
                'team_id' => $this->user->teams_id,
                'phone' => '2222222222',
            ]);

        Event::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '2222222222';
        $email = 'info@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));

        Event::assertNotDispatched(FirstLead::class);
    }

    public function testFirstLeadDiffPhones()
    {
        $this->client = factory(\App\Client::class)
            ->create([
                'team_id' => $this->user->teams_id,
                'phone' => '2222222222',
            ]);

        Event::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '3333333333';
        $email = 'info@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));
        $response->assertStatus(200);
        $this->assertDatabaseHas('clients', [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'phone' => $phone,
            'email' => $email,
        ]);

        Event::assertNotDispatched(FirstLead::class);
    }

    public function testFirstLeadJob()
    {
        Queue::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '2222222222';
        $email = 'info@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));

        Queue::assertPushedOn('emails', SendFirstLead::class);
    }

    public function testFirstLeadNotification()
    {
        Notification::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '2222222222';
        $email = 'info@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));

        Notification::assertSentTo($this->owner, SendFirstLeadForAdmin::class);
    }

    public function testFirstTextToLeadJob()
    {
        $this->homeadvisor->update([
            'active' => true,
            'text' => 'text',
        ]);

        Queue::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '2222222222';
        $email = 'info@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));

        Queue::assertPushedOn('texts', SendLeadText::class);
    }

    public function testFirstTextToLeadJobNotActive()
    {
        $this->homeadvisor->update([
            'active' => false,
            'text' => 'text',
        ]);

        Queue::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '2222222222';
        $email = 'info@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));

        Queue::assertNotPushed(SendLeadText::class);
    }

    public function testFirstTextToLeadJobEmptyText()
    {
        $this->homeadvisor->update([
            'active' => true,
            'text' => '',
        ]);

        Queue::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '2222222222';
        $email = 'info@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));

        Queue::assertNotPushed(SendLeadText::class);
    }

    public function testFirstTextToLeadJobEmptyPhone()
    {
        $this->homeadvisor->update([
            'active' => true,
            'text' => 'text',
        ]);

        Queue::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '';
        $email = 'info@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));

        Queue::assertNotPushed(SendLeadText::class);
    }

    /* 
        test that job SendFollowUpText
        is pushing on "texts" queue
    */

    public function testFollowupJob()
    {
        $this->homeadvisor->update([
            'first_followup_active' => true,
            'first_followup_text' => 'Testing text for followup',
        ]);

        Queue::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '2222222222';
        $email = 'info@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));
        
        Queue::assertPushedOn('texts', SendFollowUpText::class);

        $followup_text = $this->homeadvisor->first_followup_text;

        Queue::assertPushed(SendFollowUpText::class, function ($job) use ($followup_text) {
            return $job->text === $followup_text;
        });
    }

    /*
        test that job SendFollowUpText
        is dispatching when
        first_followup_active is true and
        second_followup_active is false
    */
    
    public function testFirstFollowupIsActiveWithoutSecond()
    {
        $this->homeadvisor->update([
            'first_followup_active' => true,
            'second_followup_active' => false,
            'first_followup_text' => 'Testing text for first followup',
            'second_followup_text' => '',
        ]);

        Queue::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '2222222222';
        $email = 'info@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));

        $followup_text = $this->homeadvisor->first_followup_text;

        Queue::assertPushed(SendFollowUpText::class, function ($job) use ($followup_text) {
            return $job->text === $followup_text;
        });
    }

    /*
        test that job SendFollowUpText
        for first followup
        is not dispatching when
        first_followup_active is false
        second_followup_active is false
    */

    public function testFollowupsNotActive()
    {
        $this->homeadvisor->update([
            'first_followup_active' => false,
            'second_followup_active' => false,
            'first_followup_text' => '',
            'second_followup_text' => '',
        ]);

        Queue::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '2222222222';
        $email = 'info@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));

        Queue::assertNotPushed(SendFollowUpText::class);
    }

    /*
        test that job SendFollowUpText
        is dispatching when
        first_followup_active is false
        second_followup_active is true
    */

    public function testSecondFollowupIsActiveWithoutFirst()
    {
        $this->homeadvisor->update([
            'first_followup_active' => false,
            'second_followup_active' => true,
            'first_followup_text' => '',
            'second_followup_text' => 'Testing text for second followup',
        ]);

        Queue::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '2222222222';
        $email = 'info@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));

        $followup_text = $this->homeadvisor->second_followup_text;

        Queue::assertPushed(SendFollowUpText::class, function ($job) use ($followup_text) {
            return $job->text === $followup_text;
        });
    }

    /*
        test that job SendFollowUpText
        is dispatching when
        first_followup_active is true
        second_followup_active is true
    */

    public function testBothFollowupsIsActive()
    {
        $this->homeadvisor->update([
            'first_followup_active' => true,
            'second_followup_active' => true,
            'first_followup_text' => 'Testing text for first followup',
            'second_followup_text' => 'Testing text for second followup',
        ]);

        Queue::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '2222222222';
        $email = 'info@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));

        Queue::assertPushed(SendFollowUpText::class, 2);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNewLeadGet()
    {
        Queue::fake();

        $firstName = 'John';
        $lastName = 'Doe';
        $phone = '5501531717';
        $email = 'id@div-art.com';

        $get = 'first_name='.$firstName.'&last_name='.$lastName.'&address1=1234+E.+LOMBARD+ST.&city=Hyattsville&state=MD&postal_code=20785&phone_primary='.$phone.'&email='.urlencode($email).'&srOID=1234566&taskOID=40133&taskName=Asphalt+Shingle+Roofing+-+Install+or+Replace&comments=ROOF+AND+GUTTERS+NEED+REPLACING&interview=What+is+the+nature+of+this+project%3F%3ACompletely+replace+roof%3B+Special+Features+for+Roof%3AGutters+and+downspouts%3B+Are+you+aware+of+any+leaks+or+damage+to+the+roof%3F%3ANo%3B+Stories+in+House%3AThree+stories+or+more%3B+Interested+in+Green+Alternatives%3AYes%3B+Request+Stage%3APlanning+%26+Budgeting%3B+Desired+Completion+Date%3A1+-+2+weeks%3B+Property+Owner%3AYes%3B+Covered+by+Insurance%3ANo%3B&match_type=market&lead_description=Market+Match&sp_entity_id=65465456&sp_company_name=Bobs+Roofing+CO';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->get($this->link->url.'?'.$get);
        $response->assertStatus(200);
        $this->assertDatabaseHas('clients', [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'phone' => $phone,
            'email' => $email,
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExistLeadGet()
    {
        Queue::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '2222222222';
        $email = 'info@div-art.com';

        $get = 'first_name='.$firstName.'&last_name='.$lastName.'&address1=1234+E.+LOMBARD+ST.&city=Hyattsville&state=MD&postal_code=20785&phone_primary='.$phone.'&email='.urlencode($email).'&srOID=1234566&taskOID=40133&taskName=Asphalt+Shingle+Roofing+-+Install+or+Replace&comments=ROOF+AND+GUTTERS+NEED+REPLACING&interview=What+is+the+nature+of+this+project%3F%3ACompletely+replace+roof%3B+Special+Features+for+Roof%3AGutters+and+downspouts%3B+Are+you+aware+of+any+leaks+or+damage+to+the+roof%3F%3ANo%3B+Stories+in+House%3AThree+stories+or+more%3B+Interested+in+Green+Alternatives%3AYes%3B+Request+Stage%3APlanning+%26+Budgeting%3B+Desired+Completion+Date%3A1+-+2+weeks%3B+Property+Owner%3AYes%3B+Covered+by+Insurance%3ANo%3B&match_type=market&lead_description=Market+Match&sp_entity_id=65465456&sp_company_name=Bobs+Roofing+CO';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->get($this->link->url.'?'.$get);
        $response->assertStatus(200);
        $this->assertDatabaseHas('clients', [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'phone' => $phone,
            'email' => $email,
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNewLeadJson()
    {
        Queue::fake();

        $firstName = 'John';
        $lastName = 'Doe';
        $phone = '5501531717';
        $email = 'id@div-art.com';

        $json = '{"firstName":"'.$firstName.'","lastName":"'.$lastName.'","address1":"5569 East Drive","city":"Redwood City","state":"CA","postalCode":"94061","phonePrimary":"'.$phone.'","email":"'.$email.'","srOid":64367469,"leadOid":192065244,"taskOid":40072,"fee":13.71,"taskName":"Gutters & Downspouts - Clean","interview":"Why Gutters Need Cleaning:Water isnt draining from the downspouts; Number of stories in house:One story; Request Stage:Ready to Hire; Desired Completion Date:Timing is flexible; Recurring Service Requested:No;","matchType":"market","leadDescription":"Market Match","spEntityId":5166999,"spCompanyName":"Testing V & G Carpet & House Cleaning","crmKey":"id_341B5D3Ek4A94k4671kA5D8kC826879CA627","zip":"94061","name":"Ginta Bero","appointment":{"appointmentOid":8668,"leadOid":192065244,"status":"Confirmed","type":"Service","icsUuid":"HA-b97290e2-1441-466a-abb3-e2fda9a94cbc.ics","start":"2015-06-09T12:00:00.000-07:00","end":"2015-06-09T14:00:00.000-07:00","lead_id":192065244,"external_id":"HA-b97290e2-1441-466a-abb3-e2fda9a94cbc.ics"},"primary_phone":"3036849684","lead_id":192065244}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));
        $response->assertStatus(200);
        $this->assertDatabaseHas('clients', [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'phone' => $phone,
            'email' => $email,
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExistLeadJson()
    {
        Queue::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '2222222222';
        $email = 'info@div-art.com';

        $json = '{"firstName":"'.$firstName.'","lastName":"'.$lastName.'","address1":"5569 East Drive","city":"Redwood City","state":"CA","postalCode":"94061","phonePrimary":"'.$phone.'","email":"'.$email.'","srOid":64367469,"leadOid":192065244,"taskOid":40072,"fee":13.71,"taskName":"Gutters & Downspouts - Clean","interview":"Why Gutters Need Cleaning:Water isnt draining from the downspouts; Number of stories in house:One story; Request Stage:Ready to Hire; Desired Completion Date:Timing is flexible; Recurring Service Requested:No;","matchType":"market","leadDescription":"Market Match","spEntityId":5166999,"spCompanyName":"Testing V & G Carpet & House Cleaning","crmKey":"id_341B5D3Ek4A94k4671kA5D8kC826879CA627","zip":"94061","name":"Ginta Bero","appointment":{"appointmentOid":8668,"leadOid":192065244,"status":"Confirmed","type":"Service","icsUuid":"HA-b97290e2-1441-466a-abb3-e2fda9a94cbc.ics","start":"2015-06-09T12:00:00.000-07:00","end":"2015-06-09T14:00:00.000-07:00","lead_id":192065244,"external_id":"HA-b97290e2-1441-466a-abb3-e2fda9a94cbc.ics"},"primary_phone":"3036849684","lead_id":192065244}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));
        $response->assertStatus(200);
        $this->assertDatabaseHas('clients', [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'phone' => $phone,
            'email' => $email,
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNewLeadEliteJson()
    {
        Queue::fake();

        $firstName = 'John';
        $lastName = 'Doe';
        $phone = '5501531717';
        $email = 'id@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));
        $response->assertStatus(200);
        $this->assertDatabaseHas('clients', [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'phone' => $phone,
            'email' => $email,
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExistLeadEliteJson()
    {
        Queue::fake();

        $firstName = 'Bill';
        $lastName = 'Down';
        $phone = '2222222222';
        $email = 'info@div-art.com';

        $json = '{"name":"Fancy Nancy","firstName":"'.$firstName.'","lastName":"'.$lastName.'","address":"123 Main St","city":"USAville","stateProvince":"PA","postalCode":"12345","primaryPhone":"'.$phone.'","phoneExt":"1234","secondaryPhone":"5558675308","secondaryPhoneExt":"1234","email":"'.$email.'","srOid":87654321,"leadOid":135911130,"taskOid":40006,"taskName":"Maid Service","spPartnerId":"54321","crmKey":"abcd-12345","contactStatus":"New HomeAdvisor Prospect - Not Contacted","comments":"I\'m looking for recurring cleaning services, please.","interview":[{"question":"What kind of location is this?","answer":"Home/Residence"},{"question":"Cleaning Type Needed","answer":"Recurring Service"},{"question":"Request Stage","answer":"Ready to Hire"},{"question":"Desired Completion Date","answer":"Within 1 week"}],"matchType":"exact","leadDescription":"Exact Match","spEntityId":88888888,"spCompanyName":"Maid Service Cleaning, LLC"}';

        $this->expectOutputString('<success>'.$this->link->success.'</success>');
        $response = $this->post($this->link->url, json_decode($json, true));
        $response->assertStatus(200);
        $this->assertDatabaseHas('clients', [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'phone' => $phone,
            'email' => $email,
        ]);
    }
}
