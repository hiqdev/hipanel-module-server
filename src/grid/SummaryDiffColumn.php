<?php

declare(strict_types=1);

namespace hipanel\modules\server\grid;

use hipanel\grid\DataColumn;
use hipanel\modules\server\models\Server;
use Jfcherng\Diff\DiffHelper;
use Jfcherng\Diff\Factory\RendererFactory;
use yii\helpers\Html;

/**
 *
 * @property-read string $css
 * @property-read void $summaryDiff
 */
class SummaryDiffColumn extends DataColumn
{
    public $format = 'html';
    public $filter = false;
    public $attribute = 'hwsummary_diff';
    private array $differOptions = ['ignoreWhitespace' => true, 'lengthLimit' => 5000];
    private array $diffRendererOptions = [
        'detailLevel' => 'char',
        'language' => 'eng',
        'lineNumbers' => false,
        'spacesToNbsp' => true,
        'resultForIdenticals' => true,
        'wrapperClasses' => ['diff-wrapper'],
    ];

    public function init()
    {
        parent::init();
        $this->grid->getView()->registerCss($this->getCss());
        $this->getSummaryDiff();
    }

    private function getSummaryDiff(): void
    {
        $this->value = function (Server $model): string {
            $hwsummary = $model->getAttribute('hwsummary');
            $hwsummary_auto = $model->getAttribute('hwsummary_auto');
            if (!$model->isTagsHidden()) {
                $model->setTags(null);
            }
            if (empty($hwsummary) || empty($hwsummary_auto)) {
                return '';
            }
            $jsonResult = DiffHelper::calculate(
                explode('/', $hwsummary),
                explode('/', $hwsummary_auto),
                'Json',
                $this->differOptions
            );
            $htmlRenderer = RendererFactory::make('Inline', $this->diffRendererOptions);

            return $htmlRenderer->renderArray(json_decode($jsonResult, true));
        };
    }

    private function getCss(): string
    {
        return Html::style('
            .diff-wrapper.diff {
                --tab-size: 4;
                background: repeating-linear-gradient(-45deg, whitesmoke, whitesmoke 0.5em, #e8e8e8 0.5em, #e8e8e8 1em);
                border-collapse: collapse;
                border-spacing: 0;
                border: 1px solid black;
                color: black;
                empty-cells: show;
                font-family: monospace;
                font-size: 13px;
                width: 100%;
                word-break: break-all;
            }
            .diff-wrapper.diff th {
                font-weight: 700;
                cursor: default;
                -webkit-user-select: none;
                user-select: none;
            }
            .diff-wrapper.diff td {
                vertical-align: baseline;
            }
            .diff-wrapper.diff td,
            .diff-wrapper.diff th {
                border-collapse: separate;
                border: none;
                padding: 1px 2px;
                background: #fff;
            }
            .diff-wrapper.diff td:empty:after,
            .diff-wrapper.diff th:empty:after {
                content: " ";
                visibility: hidden;
            }
            .diff-wrapper.diff td a,
            .diff-wrapper.diff th a {
                color: #000;
                cursor: inherit;
                pointer-events: none;
            }
            .diff-wrapper.diff thead th {
                background: #a6a6a6;
                border-bottom: 1px solid black;
                padding: 4px;
                text-align: left;
            }
            .diff-wrapper.diff tbody.skipped {
                border-top: 1px solid black;
            }
            .diff-wrapper.diff tbody.skipped td,
            .diff-wrapper.diff tbody.skipped th {
                display: none;
            }
            .diff-wrapper.diff tbody th {
                background: #cccccc;
                border-right: 1px solid black;
                text-align: right;
                vertical-align: top;
                width: 4em;
            }
            .diff-wrapper.diff tbody th.sign {
                background: #fff;
                border-right: none;
                padding: 1px 0;
                text-align: center;
                width: 1em;
            }
            .diff-wrapper.diff tbody th.sign.del {
                background: #fbe1e1;
            }
            .diff-wrapper.diff tbody th.sign.ins {
                background: #e1fbe1;
            }
            .diff-wrapper.diff.diff-html {
                white-space: pre-wrap;
                tab-size: var(--tab-size);
            }
            .diff-wrapper.diff.diff-html .ch {
                line-height: 1em;
                background-clip: border-box;
                background-repeat: repeat-x;
                background-position: left center;
            }
            .diff-wrapper.diff.diff-html .change.change-eq .old,
            .diff-wrapper.diff.diff-html .change.change-eq .new {
                background: #fff;
            }
            .diff-wrapper.diff.diff-html .change .old {
                background: #fbe1e1;
            }
            .diff-wrapper.diff.diff-html .change .new {
                background: #e1fbe1;
            }
            .diff-wrapper.diff.diff-html .change .rep {
                background: #fef6d9;
            }
            .diff-wrapper.diff.diff-html .change .old.none,
            .diff-wrapper.diff.diff-html .change .new.none,
            .diff-wrapper.diff.diff-html .change .rep.none {
                background: transparent;
                cursor: not-allowed;
            }
            .diff-wrapper.diff.diff-html .change ins,
            .diff-wrapper.diff.diff-html .change del {
                font-weight: bold;
                text-decoration: none;
            }
            .diff-wrapper.diff.diff-html .change ins {
                background: #94f094;
            }
            .diff-wrapper.diff.diff-html .change del {
                background: #f09494;
            }
        ');
    }

}
