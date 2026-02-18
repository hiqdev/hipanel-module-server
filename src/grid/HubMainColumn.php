<?php declare(strict_types=1);

namespace hipanel\modules\server\grid;

use hipanel\grid\MainColumn;
use hipanel\helpers\Url;
use Yii;

class HubMainColumn extends MainColumn
{
    public function init(): void
    {
        parent::init();
        $this->prepareNotes();
    }

    private function prepareNotes(): void
    {
        $user = Yii::$app->user;
        if (!$user->can('hub.update')) {
            $this->note = [];

            return;
        }
        $this->note = ['note', 'description'];
        $this->noteOptions = array_merge([
            'note' => [
                'url' => Url::to('set-note'),
            ],
            'description' => [
                'url' => Url::to('set-description'),
            ],
        ], $this->noteOptions);
    }
}
