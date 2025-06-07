<?php

namespace App\Livewire;

use App\Jobs\SendCandidateStatusEmailJob;
use App\Models\User;
use App\Models\Vacancy;
use App\Traits\LogsActivity;
use Livewire\Component;

class Candidates extends Component
{
    use LogsActivity;

    public Vacancy $vacancy;
    public array $statuses = [];

    public array $originalStatuses = [];

    public function mount(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;

        foreach ($vacancy->users as $user) {
            $status = $user->pivot->status;
            $this->statuses[$user->id] = $status;
            $this->originalStatuses[$user->id] = $status;
        }
    }

    public function setStatus($userId, $status)
    {
        $this->statuses[$userId] = $status;
    }

    public function saveStatuses()
    {
        foreach ($this->statuses as $userId => $newStatus) {
            $originalStatus = $this->originalStatuses[$userId] ?? null;

            if ($newStatus !== $originalStatus) {
                $this->vacancy->users()->updateExistingPivot($userId, [
                    'status' => $newStatus,
                ]);

                $user = User::find($userId);

                $this->logActivity(
                    action: $newStatus === 'accepted' ? 'accepted_candidate' : 'rejected_candidate',
                    targetType: 'App\Models\Vacancy',
                    targetId: $this->vacancy->id,
                    description: "Vacancy status updated to '{$newStatus}' for user {$user->name}"
                );

                if (in_array($newStatus, ['accepted', 'rejected'])) {
                    SendCandidateStatusEmailJob::dispatch($this->vacancy, $user, $newStatus);
                }
            }
        }

        $this->originalStatuses = $this->statuses;

        session()->flash('message', __('Candidate statuses updated successfully'));
    }


    public function render()
    {
        return view('livewire.candidates')->layout('layouts.app');
    }
}
