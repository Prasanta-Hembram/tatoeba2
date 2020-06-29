<?php
/**
    Tatoeba Project, free collaborative creation of languages corpuses project
    Copyright (C) 2020  Gilles Bedel

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace App\Event;

use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

class LinksListener implements EventListenerInterface {
    public function implementedEvents() {
        return array(
            'Model.Sentence.saved' => 'updateLanguageInLinksTable',
        );
    }

    public function updateLanguageInLinksTable($event, $entity, $options) {
        $Links = TableRegistry::getTableLocator()->get('Links');
        $sentence = $event->getData('data');
        if ($sentence->id && $sentence->isDirty('lang')) {
            $Links->updateLanguage($sentence->id, $sentence->lang);
        }
    }
}
