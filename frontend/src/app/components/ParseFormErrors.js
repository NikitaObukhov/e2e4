angular
  .module('app')
  .component('parseFormErrors', {
    templateUrl: 'app/components/ParseFormErrors.html',
    controller: ParseFormErrors,
    bindings: {
      options: '=',
      form: '='
    }
  });


function ParseFormErrors() {
  window.form_errors = this;
}

