<?php


namespace hipanel\modules\server\grid;


use hipanel\grid\MainColumn;
use hipanel\helpers\Url;
use hipanel\modules\server\models\Server;
use hipanel\widgets\Label;
use Yii;

class ServerNameColumn extends MainColumn
{
    public function init()
    {
        parent::init();

        $this->attribute = 'name';
        $this->filterAttribute = 'name_ilike';

        $this->prepareBadges();
        $this->prepareNotes();
    }

    private function prepareBadges(): void
    {
        $this->badges = function (Server $model): string {
            $badges = '';
            if (Yii::$app->user->can('support')) {
                if ($model->wizzarded) {
                    $badges .= Label::widget(['label' => 'W', 'tag' => 'sup', 'color' => 'success']);
                }
            }
            $badges .= Label::widget(['label' => Yii::t('hipanel:server', $model->type_label), 'tag' => 'sup', 'color' => 'info']);

            return $badges;
        };
    }

    private function prepareNotes(): void
    {
        $user = Yii::$app->user;
        $canSeeLabel = $user->can('server.see-label') && $user->can('owner-staff');
        $canSetLabel = $user->can('server.set-label');
        $canSetNote = $user->can('server.set-note');

        $this->note = array_filter(['note', $canSeeLabel ? 'label' : null]);
        $this->noteOptions = array_filter([
            'note' => [
                'url' => $canSetNote ? Url::to('set-note') : null,
            ],
            'label' => [
                'url' => $canSetLabel ? Url::to('set-label') : null,
            ],
        ]);
    }
}
