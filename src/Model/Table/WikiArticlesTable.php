<?php
namespace App\Model\Table;

use App\Lib\LanguagesLib;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\ORM\Table;

class WikiArticlesTable extends Table
{
    public static function defaultConnectionName() {
        return 'wiki';
    }

    public function getArticleTranslations($lang, $slug) {
        try {
            $article = $this->find()
                ->select(['group_id'])
                ->where(compact('lang', 'slug'))
                ->first();

            if (!$article) {
                return [];
            }
            $group_id = $article->group_id;

            return $this->find()
                ->select(['lang', 'slug'])
                ->where(compact('group_id'))
                ->enableHydration(false)
                ->combine('lang', 'slug')
                ->toArray();
        }
        catch (\PDOException $e) {
            if ($this->getConnection()->isQueryLoggingEnabled()) {
                Log::error('Error while connecting to the wiki: '. $e->getMessage());
            }
            return [];
        }
    }

    public function getWikiLink($englishSlug) {
        $uiLang = LanguagesLib::languageTag(Configure::read('Config.language'));
        $lang = 'en';
        $slug = $englishSlug;

        $articles = $this->getArticleTranslations($lang, $slug);
        if (isset($articles[$uiLang])) {
            $lang = $uiLang;
            $slug = $articles[$uiLang];
        }

        $slug = urlencode($slug);
        return "https://$lang.wiki.tatoeba.org/articles/show/$slug";
    }

    public function wikiLinkLocalizer() {
        return function($slug) { return $this->getWikiLink($slug); };
    }

    public function initialize(array $config) {
        parent::initialize($config);
        $this->setTable('articles');
    }

    public function save($article, $options = []) {
        // The PDO connection is set to read-only already,
        // but let's be extra careful about not writing anything
        return false;
    }
}
