<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\entrycount\controllers;

use Craft;
use craft\web\Controller;
use putyourlightson\entrycount\EntryCount;

/**
 * EntryCountController
 */
class EntryCountController extends Controller
{
    protected $allowAnonymous = ['reset'];

    /**
     * Reset count
     */
    public function actionReset()
    {
        $entryId = Craft::$app->getRequest()->getRequiredParam('entryId');

        EntryCount::$plugin->entryCount->reset($entryId);

        Craft::$app->getSession()->setNotice(Craft::t('entry-count', 'Entry count reset.'));

        return $this->redirect('entry-count');
    }

    /**
     * Reset all
     */
    public function actionResetAll()
    {
        EntryCount::$plugin->entryCount->resetAll();

        Craft::$app->getSession()->setNotice(Craft::t('entry-count', 'All entry counts reset.'));

        return $this->redirect('entry-count');
    }
}
