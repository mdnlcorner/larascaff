<?php

namespace Mulaidarinull\Larascaff\Notifications;

use Illuminate\Notifications\DatabaseNotification;

class NotificationRoute
{
    public function show(DatabaseNotification $notification)
    {
        $model = (new $notification->model_type)->findOrFail($notification->model_id);
        if ($notification->priority != 1) {
            $notification->read_at = now();
            $notification->save();
        }

        return redirect($model->routeNotification()."?tableAction={$notification->data['action']}&tableActionId=".$model->{$model->getRouteKeyName()});
    }
}
