'use strict';
angular.module('search')
  .factory('SearchRequestRepository', function (AbstractRepository, Backend, $injector) {
      function SearchRequest() {
        this.getNameOfDataColumn = function() {
          return this.type_names_singular.join(', ');
        };
        this.getHumanReadableName = function() {
          return 'Результат поиска '+ this.getGenetivePluralNames().join(', ') + ' на сайте ' + this.website_page.website.domain + ' ' + moment(this.created_at).format('HH:mm DD.MM.YYYY');
        };
        this.getGenetivePluralNames = function() {
          return _.map(this.type.split(','), function(type) {
            switch(type) {
              case 'text':
                return 'текста';
              case 'link':
                return 'ссылок';
              case 'image':
                return 'картинок';
            }
          });
        };
      };

      var repository =  new AbstractRepository(Backend, 'search/', SearchRequest, function(element, isCollection, what, backend) {
        if (isCollection) {
          return element;
        }
        var instance = $injector.instantiate(SearchRequest);
        _.extend(instance, element);
        return instance;
      });
      var defaultParams = {};
      repository.get = function(id) {
        return this.restangular.one(this.route, id).get({expand: 'typeNamesSingular,websitePage,website'});
      };
      repository.history = function(params, scope) {
        return this.restangular.all(this.route + '/requests').getList(_.merge(defaultParams, {expand: 'searchRequest,searchResultsCount,websitePage,website,typeNamesPlural'}, params));
      };
      repository.create = function(searchRequest) {
        if (angular.isUndefined(searchRequest.originalElement) === false) {
          delete searchRequest.originalElement;
        }
        return this.restangular.all(this.route + '/do?expand='+ 'searchRequest,searchResultsCount,websitePage,website,typeNamesPlural').post(searchRequest);
      };
      return repository;
    });

