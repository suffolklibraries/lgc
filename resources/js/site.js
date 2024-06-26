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
import PasswordValidation from './passwordValidation';

(() => {
  Menu();
  Events();
  Map();
  Search();
  Tabs();
  LinkIcons();
  PageZoom();
  EventSubmissionForm();
  PasswordValidation();
})();

window.Alpine = Alpine

Alpine.plugin(focus)
Alpine.start()
