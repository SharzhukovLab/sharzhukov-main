<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Reaction;
use App\Models\UserProject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ReactionsController extends Controller
{
    protected function resolveSubject(string $type, int $id): Model
    {
        $map = [
            'news'    => News::class,
            'project' => UserProject::class,
        ];

        abort_unless(isset($map[$type]), 404);

        return $map[$type]::findOrFail($id);
    }

    /** GET /api/reactions/{type}/{id} — счётчики + что поставил текущий юзер */
    public function index(Request $request, string $type, int $id)
    {
        $subject = $this->resolveSubject($type, $id);

        return response()->json([
            'counts'         => $subject->reactionCounts(),
            'total'          => $subject->reactionTotal(),
            'user_reactions' => $subject->userReactions($request->user()?->id),
        ]);
    }

    /** POST /api/reactions/toggle — toggle / switch */
    public function toggle(Request $request)
    {
        $data = $request->validate([
            'subject_type' => ['required', 'in:news,project'],
            'subject_id'   => ['required', 'integer', 'min:1'],
            'type'         => ['required', 'in:' . implode(',', Reaction::TYPES)],
        ]);

        $user = $request->user();
        $subject = $this->resolveSubject($data['subject_type'], $data['subject_id']);

        $existing = Reaction::query()
            ->forSubject($subject)
            ->where('user_id', $user->id)
            ->first();

        if ($existing && $existing->type === $data['type']) {
            // тот же тип → снять
            $existing->delete();
            $action = 'removed';
        } elseif ($existing) {
            // другой тип → переключить
            $existing->update(['type' => $data['type']]);
            $action = 'switched';
        } else {
            Reaction::create([
                'reactable_type' => $subject->getMorphClass(),
                'reactable_id'   => $subject->getKey(),
                'user_id'        => $user->id,
                'type'           => $data['type'],
            ]);
            $action = 'added';
        }

        // свежие счётчики
        $counts = Reaction::countsFor($subject);

        return response()->json([
            'action'         => $action,
            'counts'         => $counts,
            'total'          => array_sum($counts),
            'user_reactions' => $subject->userReactions($user->id),
        ]);
    }
}
