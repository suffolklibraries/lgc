import Menu from './menu';
import Events from './events';
import Map from './map';
import Search from './search';
import Tabs from './tabs';
import LinkIcons from './linkIcons';
import PageZoom from './page-zoom';
import EventSubmissionForm from './eventSubmissionForm';
import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'

(() => {
  Menu();
  Events();
  Map();
  Search();
  Tabs();
  LinkIcons();
  PageZoom();
  EventSubmissionForm();
})();

window.Alpine = Alpine

Alpine.plugin(focus)
Alpine.start()
