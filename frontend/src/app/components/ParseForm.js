function parseFormConfig(formlyConfigProvider) {
  formlyConfigProvider.setWrapper({
    template: '<formly-transclude></formly-transclude><parse-form-errors options="options" form="form"></parse-form-errors>',
    types: ['input', 'checkbox', 'select', 'textarea', 'radio', 'input-loader']
  });
}

function parseForm(SearchRequestRepository, $scope, $rootScope) {

  var $ctrl = this;
  var textParser = ["text"];
  this.model = {parsers: textParser};
  this.result;

  $scope.$watch('$ctrl.model', function(model) {
    if (model.parsers.indexOf('text') < 0) {
      delete model.searchText;
    }
  }, true);
  this.form;

  this.errors = [];

  this.submit = function() {
    if ($ctrl.form.$valid) {
      SearchRequestRepository.create($ctrl.model).then(function(searchResult) {
        $ctrl.errors = [];
        $ctrl.result = searchResult;
        $rootScope.$broadcast('parseForm.newResult', searchResult);
      }, function(response) {
        $ctrl.errors = response.data.errors;
      });
    }
  };


  this.formFields = [
    {
      key: 'url',
      type: 'input',
      validators: {
        domain: {
          expression: function(viewValue, modelValue) {
            var value = modelValue || viewValue;
            return /^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$/.test(value);
          },
          message: function(viewValue) {
            return viewValue === undefined || viewValue.trim().length === 0 ? 'Домен обязателен к заполнению' : viewValue + ' неверный домен';
          }
        }
      },
      templateOptions: {
        label: '',
        required: true,
        placeholder: 'e2e4online.ru'
      }
    },
    {
      key: "parsers",
      type: "radio",
      defaultValue: textParser,
      templateOptions: {
        label: "",
        required: true,
        options: [
          {
            name: "Текст",
            value: textParser
          },
          {
            name: "Ссылки",
            value: ["link"]
          },
          {
            name: "Картинки",
            value: ["image"]
          }
        ]
      }
    },
    {
      key: 'searchText',
      type: 'input',
      hideExpression: function() {
        return $ctrl.model.parsers.indexOf('text') < 0;
      },
      validators: {
        searchText: {
          expression: function(viewValue, modelValue) {
            if ($ctrl.model.parsers.indexOf('text') < 0) {
              return true;
            }
            var value = modelValue || viewValue || '';
            return value.trim().length >= 3;
          },
          message: '"Текст для поиска должен быть длиной в 3 символа и более"'
        }
      },
      templateOptions: {
        label: '',
        required: true,
        placeholder: 'Текст для поиска'
      }
    }
  ];
}

angular
  .module('app')
  .config(parseFormConfig)
  .component('parseForm', {
    templateUrl: 'app/components/ParseForm.html',
    controller: parseForm
  });