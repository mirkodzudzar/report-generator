<?php

namespace Tests\Feature\TaskReports;

use Tests\TestCase;
use App\Models\User;
use App\Jobs\GenerateReportJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ReportNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\SendQueuedNotifications;

class CreateReportTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Bus::fake();
        Queue::fake();
        Storage::fake();
        Notification::fake();

        $this->seed(UsersTableSeeder::class);
    }

    public function test_guest_cannot_visit_create_task_report_page(): void
    {
        $this->get(route('tasks.reports.create'))
            ->assertRedirect(route('login'))
            ->assertStatus(302);
    }

    public function test_user_can_visit_create_task_report_page(): void
    {
        $this->actingAs(User::first())
            ->get(route('tasks.reports.create'))
            ->assertStatus(200);
    }

    public function test_guest_cannot_create_task_report(): void
    {
        $this->post(route('tasks.reports.store'), ['user_id' => User::first()])
            ->assertRedirect(route('login'))
            ->assertStatus(302);
    }

    public function test_user_cannot_create_task_report_for_non_existing_user(): void
    {
        $user = User::first();

        $this->actingAs($user)
            ->fromRoute('tasks.reports.create')
            ->post(route('tasks.reports.store'), ['user_id' => 9999])
            ->assertRedirect(route('tasks.reports.create'))
            ->assertInvalid('user_id')
            ->assertStatus(302);
    }

    public function test_user_can_create_task_report_for_himself(): void
    {
        $user = User::first();

        $this->actingAs($user)
            ->fromRoute('tasks.reports.create')
            ->post(route('tasks.reports.store'), ['user_id' => $user->id])
            ->assertRedirect(route('tasks.reports.create'))
            ->assertStatus(302);
    }

    public function test_user_can_create_task_report_for_other_users(): void
    {
        $user = User::first();

        $this->actingAs(User::factory()->create())
            ->fromRoute('tasks.reports.create')
            ->post(route('tasks.reports.store'), ['user_id' => $user->id])
            ->assertRedirect(route('tasks.reports.create'))
            ->assertStatus(302);
    }

    public function test_generate_report_job_is_dispatched(): void
    {
        $user = User::first();

        $this->actingAs($user)
            ->fromRoute('tasks.reports.create')
            ->post(route('tasks.reports.store'), ['user_id' => $user->id])
            ->assertRedirect(route('tasks.reports.create'))
            ->assertStatus(302);

        Bus::assertDispatched(GenerateReportJob::class, function ($job) use ($user) {
            return $job->user->id === $user->id;
        });
    }
}
