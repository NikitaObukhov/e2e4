angular
  .module('app')
  .component('pageContent', {
    templateUrl: function ($element, $attrs) {
      window.el = $element;
      window.attrs = $attrs;
      return 'app/components/LinkContent.html';
    },
    controller: PageContent,
    bindings: {
      content: '=',
      template: '=view'
    }
  });

/** @ngInject */
function PageContent() {

}
