<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\entrycount\controllers;

use Craft;
use craft\web\Controller;
use putyourlightson\entrycount\EntryCount;
use yii\web\Response;

class EntryCountController extends Controller
{
    /**
     * Reset count
     */
    public function actionReset(): Response
    {
        $entryId = Craft::$app->getRequest()->getRequiredParam('entryId');
        EntryCount::$plugin->entryCount->reset($entryId);
        Craft::$app->getSession()->setNotice(Craft::t('entry-count', 'Entry count reset.'));

        return $this->redirect('entry-count');
    }
}
