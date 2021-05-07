<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets;

use hipanel\modules\server\models\Osimage;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\Html;

/**
 * Class OSFormatter.
 *
 * Renders a formatted information about OS
 *
 * @uses \yii\bootstrap\Modal
 * @uses \app\modules\server\models\Osimage
 * @author SilverFire
 */
class OSFormatter extends Widget
{
    /**
     * @var array array of OsImages models
     */
    public $osimages = [];

    /**
     * @var Osimage model
     */
    public $osimage;

    /**
     * @var string osimage code-name
     */
    public $imageName;

    /**
     * @var bool whether to display a button with modal pop-up, containing OS soft information
     */
    public $infoCircle = true;

    public function init()
    {
        parent::init();

        if (is_array($this->osimages)) {
            foreach ($this->osimages as $osimage) {
                if ($osimage->osimage === $this->imageName) {
                    $this->osimage = $osimage;
                    break;
                }
            }
        }
    }

    /**
     * @return string html code of definition list
     */
    public function generateOSInfo()
    {
        $html = Html::beginTag('table', ['class' => 'table table-condensed table-striped']);
        $soft = [];
        if ($this->osimage->softpack['soft']) {
            $soft = $this->osimage->softpack['soft'];
        } else if ($this->osimage->softpack['packages']) {
            $soft = $this->osimage->softpack['packages'];
        }

        foreach ($soft as $item) {
            $html .= Html::beginTag('tr');
            $html .= Html::tag('th', Html::encode($item['name']) . ' ' . Html::encode($item['version']), ['class' => 'text-right']);
            $html .= Html::tag('td', str_replace(',', ', ', Html::encode($item['description'])));
            $html .= Html::endTag('tr');
        }

        $html .= Html::endTag('table');

        return $html;
    }

    /**
     * Renders info-circle with modal popup.
     */
    public function generateInfoCircle()
    {
        Modal::begin([
            'toggleButton' => [
                'class' => 'fa fa-info text-info os-info-popover',
                'label' => '',
            ],
            'header'       => Html::tag('h4', Html::encode($this->osimage->getFullOsName())),
            'size'         => Modal::SIZE_LARGE,
        ]);
        echo Html::tag('div', $this->generateOSInfo(), [
            'class' => 'table-responsive',
        ]);
        Modal::end();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        if (!$this->osimage instanceof Osimage) {
            echo Html::encode($this->imageName);

            return;
        }

        echo Html::encode($this->osimage->getFullOsName());
        echo '&nbsp;';
        if ($this->osimage->hasSoftPack() && $this->infoCircle) {
            $this->generateInfoCircle();
        }
    }
}
