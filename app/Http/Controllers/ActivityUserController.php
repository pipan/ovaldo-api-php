<?php

namespace App\Http\Controllers;

use App\Http\Bearer\Bearer;
use App\Http\ResponseError;
use App\Http\Schema;
use App\Repository\ActivityRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ActivityUserController extends Controller
{
    private $activityRepository;
    private $bearer;

    public function __construct(ActivityRepository $activityRepository, Bearer $bearer)
    {
        $this->activityRepository = $activityRepository;
        $this->bearer = $bearer;
    }

    public function __invoke($id, Request $request)
    {
        $activity = $this->activityRepository->find($id);
        if (!$activity) {
            return ResponseError::resourceNotFound();
        }
        $validator = Validator::make($request->all(), [
            'action' => ['bail', 'required', Rule::in(['join', 'leave'])]
        ]);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $action = $request->input('action');
        if ($action === 'join') {
            $this->activityRepository->join($activity, $this->bearer->getUser()->id);
        } else {
            $this->activityRepository->leave($activity, $this->bearer->getUser()->id);
        }

        $activity = $this->activityRepository->find($id);
        return response([
            'activity' => Schema::forActivity()->adapt($activity->toArray()),
            'user' => Schema::forUser()->adapt($this->bearer->getUser()->toArray())
        ]);
    }
}